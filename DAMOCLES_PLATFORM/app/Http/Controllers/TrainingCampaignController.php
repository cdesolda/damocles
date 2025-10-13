<?php

namespace App\Http\Controllers;

use Exception;
use App\Jobs\SendTrainingEmails;
use App\Models\LLM;
use App\Models\TrainingCampaign;
use App\Models\User;
use App\Models\TrainingPrompt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Threat;
use App\Models\UserHfThreat;
use App\Models\UserTrainingCampaign;
use App\Services\LLMService;
use Illuminate\Support\Facades\DB;

class TrainingCampaignController extends Controller
{
    protected $llmService;

    public function __construct(LLMService $llmService)
    {
        $this->llmService = $llmService;
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'Evaluator') {
            $trainingCampaigns = TrainingCampaign::where('evaluator_id', $user->id)->get();
        } elseif ($user->role === 'User') {
            $userTrainingCampaigns = UserTrainingCampaign::where('user_id', $user->id)->get();
            $trainingCampaigns = TrainingCampaign::whereIn('id', $userTrainingCampaigns->pluck('training_campaign_id'))
                ->where('state', 'Live')
                ->get();

            foreach ($trainingCampaigns as $trainingCampaign) {
                $trainingCampaign->user_has_training = $userTrainingCampaigns->where('training_campaign_id', $trainingCampaign->id)->whereNotNull('training')->isNotEmpty();
                $trainingCampaign->user_has_done = $userTrainingCampaigns->where('training_campaign_id', $trainingCampaign->id)->where('done')->isNotEmpty();
            }
        }

        return view('training-campaign.training-campaign', compact('trainingCampaigns'));
    }

    public function new()
    {
        $threats = Threat::all();

        return view('training-campaign.new-training-campaign', compact('threats'));
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'expirationDate' => ['required'],
            'threatId' => ['required', 'integer'],
            'type' => ['required', 'string'],
            'evaluatorId' => ['required', 'integer'],
            'state' => ['required', 'string', 'max:255'],
        ]);

        $validatedData['expiration_date'] = $validatedData['expirationDate'];
        $validatedData['threat_id'] = $validatedData['threatId'];
        $validatedData['evaluator_id'] = $validatedData['evaluatorId'];

        $trainingCampaign = TrainingCampaign::create($validatedData);

        return redirect()->route('training-campaign.simulation', ['trainingCampaign' => $trainingCampaign->id]);
    }

    public function simulation($trainingCampaignId)
    {
        $trainingCampaign = TrainingCampaign::findOrFail($trainingCampaignId);
        $users = User::where('role', 'User')->where('type', 'Fake')->get();

        $userHfThreat = UserHfThreat::whereIn('user_id', $users->pluck('id'))
            ->with('user')
            ->with('humanFactor')
            ->with('threat')
            ->get();

        $groupedByUser = $userHfThreat->groupBy('user_id');

        $users = $groupedByUser->map(function ($userThreats) {
            $user = $userThreats->first()->user;

            $threatsByUser = $userThreats->groupBy('hf_id')->map(function ($hfThreats) {
                return $hfThreats->map(function ($hfThreat) {
                    return [
                        'hf' => $hfThreat->humanFactor->name,
                        'threat' => $hfThreat->threat->name,
                        'severityLevel' => number_format($hfThreat->severityLevel, 2),
                    ];
                });
            });

            return [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'surname' => $user->surname,
                    'dob' => $user->dob,
                    'gender' => $user->gender,
                    'company_role' => $user->company_role,
                ],
                'threats' => $threatsByUser,
            ];
        });

        $llms = LLM::all();
        $prompts = TrainingPrompt::where('type', $trainingCampaign->type)->get();

        return view('training-campaign.simulation-training-campaign', compact('trainingCampaign', 'users', 'llms', 'prompts'));
    }

    public function saveSimulation(Request $request)
    {
        $validatedData = $request->validate([]);
        $trainingCampaign = TrainingCampaign::create($validatedData);

        return redirect()->route('training-campaign.users', ['trainingCampaign' => $trainingCampaign->id]);
    }

    public function generateTrainings(Request $request)
    {
        $validatedData = $request->validate([
            'trainingCampaignId' => ['required', 'integer'],
            'llmId' => ['required', 'integer'],
            'evaluatorId' => ['required', 'integer'],
            'prompt' => ['required', 'string'],
            'selectedUserIds' => ['required'],
        ]);

        $trainingCampaign = TrainingCampaign::findOrFail($validatedData['trainingCampaignId']);
        $llm = LLM::findOrFail($validatedData['llmId']);

        $trainingCampaign->llm_id = $llm->id;
        $trainingCampaign->prompt = $validatedData['prompt'];
        $trainingCampaign->save();

        $promptTemplate = $validatedData['prompt'];

        $selectedFakeUsers = json_decode($request->input('selectedUserIds'), true);
        $selectedFakeUserIds = array_column($selectedFakeUsers, 'id');

        $fakeUsers = User::whereIn('id', $selectedFakeUserIds)->get();

        $generatedTrainings = [];

        foreach ($fakeUsers as $user) {

            $userHfThreat = UserHfThreat::where('user_id', $user->id)
                ->with('user')
                ->with('humanFactor')
                ->with('threat')
                ->get();

            $user = $userHfThreat->first()->user;

            $humanFactors = $userHfThreat->groupBy('hf_id')->map(function ($hfThreats) {
                return $hfThreats->map(function ($hfThreat) {
                    return [
                        'humanFactor' => $hfThreat->humanFactor->name,
                        'threat' => $hfThreat->threat->name,
                        'minEducationLevel' => 0,
                        'maxEducationLevel' => $hfThreat->humanFactor->likert_scales,
                        'educationLevel' => number_format($hfThreat->severityLevel, 2),
                    ];
                });
                return [
                    'threats' => $threatsByUser,
                ];
            });

            $userData = [
                'user' => $user->only(['name', 'surname', 'dob', 'company_role']),
                'humanFactors' => $humanFactors,
            ];

            $customPrompt = str_replace('JSON', 'JSON ' . json_encode($userData), $promptTemplate);

            $generatedTraining = $this->generateTraining($llm, $customPrompt);

            if (isset($generatedTraining['error']) && $generatedTraining['error']) {
                return view('training-campaign.show-training', [
                    'trainingCampaign' => $trainingCampaign,
                    'training' => $generatedTraining,
                    'status' => $generatedTraining['status'],
                    'user' => $userData,
                    'error' => true,
                ]);
            } else {
                $generatedTrainings[] = [
                    'userData' => $userData,
                    'trainingContent' => $generatedTraining['content'],
                ];
            }
        }

        return view('training-campaign.generated-training', [
            'trainingCampaign' => $trainingCampaign,
            'generatedTrainings' => $generatedTrainings,
        ]);
    }

    public function generateUserTraining($userId, $trainingCampaignId)
    {
        $user = User::findOrFail($userId);

        $trainingCampaign = TrainingCampaign::findOrFail($trainingCampaignId);
        $llm = LLM::findOrFail($trainingCampaign->llm_id);

        $userTrainingCampaign = UserTrainingCampaign::where('user_id', $userId)->where('training_campaign_id', $trainingCampaignId)->first();

        if ($userTrainingCampaign->training != null) {
            return view('training-campaign.show-training', [
                'trainingCampaign' => $trainingCampaign,
                'userTrainingCampaign' => $userTrainingCampaign,
                'error' => false,
            ]);
        }

        $userHfThreats = UserHfThreat::where('user_id', $userId)->where('threat_id', $trainingCampaign->threat_id)->get();

        $humanFactors = [];
        foreach ($userHfThreats as $userHfThreat) {
            $humanFactors[] = [
                'humanFactor' => $userHfThreat->humanFactor->name,
                'minEducationLevel' => 0,
                'maxEducationLevel' => $userHfThreat->humanFactor->likert_scales,
                'educationLevel' => round($userHfThreat->severityLevel, 2),
            ];
        }

        $userData = [
            'user' => $user->only(['name', 'surname', 'dob', 'company_role']),
            'humanFactors' => $humanFactors,
        ];

        $customPrompt = str_replace('JSON', 'JSON ' . json_encode($userData), $trainingCampaign->prompt);

        $generatedTraining = $this->generateTraining($llm, $customPrompt);

        if (isset($generatedTraining['error']) && $generatedTraining['error']) {
            return view('training-campaign.show-training', [
                'trainingCampaign' => $trainingCampaign,
                'training' => $generatedTraining,
                'status' => $generatedTraining['status'],
                'error' => true,
            ]);
        } else {
            $userTrainingCampaign->training = $generatedTraining['content'];
            $userTrainingCampaign->save();

            return view('training-campaign.show-training', [
                'trainingCampaign' => $trainingCampaign,
                'training' => $generatedTraining['content'],
                'error' => false,
            ]);
        }
    }

    public function generateTestUserTraining($userTrainingCampaignId)
    {
        $userTrainingCampaign = UserTrainingCampaign::findOrFail($userTrainingCampaignId);
        $trainingCampaign = TrainingCampaign::findOrFail($userTrainingCampaign->training_campaign_id);
        $llm = LLM::findOrFail($trainingCampaign->llm_id);

        $customPrompt = "Given this training: \"" . $userTrainingCampaign->training . "\" \n Generate only the JSON with 5 questions with 5 multiple choise where only 1 is right with this structure: {'questions':[{'question':'','choices':['','','','',''],'correct_answer':''},{'question':'','choices':['','','','',''],'correct_answer':''},{'question':'','choices':['','','','',''],'correct_answer':''},{'question':'','choices':['','','','',''],'correct_answer':''},{'question':'','choices':['','','','',''],'correct_answer':''}]}";

        $generatedTest = $this->generateTraining($llm, $customPrompt);

        $rawJson = $generatedTest['content'];

        $rawJson = str_replace('```json', '', $rawJson);
        $rawJson = str_replace('```', '', $rawJson);

        $decodedData = json_decode($rawJson, true);

        if (isset($generatedTest['error']) && $generatedTest['error']) {
            return view('training-campaign.show-test-training', [
                'userTrainingCampaign' => $userTrainingCampaign,
                'questions' => $decodedData,
                'status' => $generatedTest['status'],
                'error' => true,
            ]);
        } else {
            return view('training-campaign.show-test-training', [
                'userTrainingCampaign' => $userTrainingCampaign,
                'questions' => $decodedData,
                'error' => false,
            ]);
        }
    }

    public function doneUserTraining($userTrainingCampaignId)
    {
        $userTrainingCampaign = UserTrainingCampaign::findOrFail($userTrainingCampaignId);

        if (!$userTrainingCampaign->done) {
            $userTrainingCampaign->done = true;
            $userTrainingCampaign->save();
        }

        $user = auth()->user();

        if ($user->role === 'Evaluator') {
            $trainingCampaigns = TrainingCampaign::where('evaluator_id', $user->id)->get();
        } elseif ($user->role === 'User') {
            $userTrainingCampaigns = UserTrainingCampaign::where('user_id', $user->id)->get();
            $trainingCampaigns = TrainingCampaign::whereIn('id', $userTrainingCampaigns->pluck('training_campaign_id'))
                ->where('state', 'Live')
                ->get();

            foreach ($trainingCampaigns as $trainingCampaign) {
                $trainingCampaign->user_has_training = $userTrainingCampaigns->where('training_campaign_id', $trainingCampaign->id)->whereNotNull('training')->isNotEmpty();
                $trainingCampaign->user_has_done = $userTrainingCampaigns->where('training_campaign_id', $trainingCampaign->id)->where('done')->isNotEmpty();
            }
        }

        return view('training-campaign.training-campaign', compact('trainingCampaigns'));
    }

    public function users($trainingCampaignId)
    {
        $trainingCampaign = TrainingCampaign::findOrFail($trainingCampaignId);

        $users = User::where('role', 'User')->where('type', 'Real')->where('is_active', true)->where('is_accept', true)->get();

        return view('training-campaign.users-training', compact('users', 'trainingCampaignId'));
    }

    public function saveUsersTraining(Request $request)
    {
        $validatedData = $request->validate([
            'trainingCampaignId' => ['required', 'integer'],
            'usersIds' => ['required'],
            'usersIds.*' => ['required'],
        ]);

        $trainingCampaignId = $validatedData['trainingCampaignId'];
        $usersIds = explode(',', $validatedData['usersIds']);

        foreach ($usersIds as $userId) {
            UserTrainingCampaign::create([
                'user_id' => $userId,
                'training_campaign_id' => $trainingCampaignId,
            ]);
        }

        $this->changeState($trainingCampaignId, 'Ready');
        return redirect()->route('training-campaign.index')->with('success', 'Emails assigned successfully.');
    }

    public function changeState($trainingCampaignId, $state)
    {
        $trainingCampaign = TrainingCampaign::findOrFail($trainingCampaignId);
        $trainingCampaign->state = $state;
        $trainingCampaign->save();

        if ($state != 'Draft' && $state != 'Ready') {

            if ($state == 'Live') {

                $userTrainingCampaigns = UserTrainingCampaign::where('training_campaign_id', $trainingCampaign->id)->get();
                $userIds = $userTrainingCampaigns->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->get();

                DB::beginTransaction();
                try {
                    foreach ($users as $user) {
                        try {
                            // Dispatch the job
                            SendTrainingEmails::dispatch($user, $trainingCampaign);
                        } catch (\Exception $e) {
                            // Handle exception if necessary
                        }
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['error' => 'Failed to dispatch jobs: ' . $e->getMessage()], 500);
                }

                return redirect()->back()->with('success', 'Campaign start successfully!');
            }

            if ($state == 'Completed') {
                return redirect()->back()->with('success', 'Campaign stopped successfully!');
            }

            return redirect()->back();
        }
    }

    public function analyse($trainingCampaignId)
    {
        $trainingCampaign = TrainingCampaign::findOrFail($trainingCampaignId);
        $userTrainings = UserTrainingCampaign::where('training_campaign_id', $trainingCampaignId)->get();

        $countUserTrainingsGenerated = UserTrainingCampaign::where('training_campaign_id', $trainingCampaignId)
            ->whereNotNull('training')
            ->count();
        $countUserTrainingsDone = UserTrainingCampaign::where('training_campaign_id', $trainingCampaignId)->where('done', true)->count();

        return view('training-campaign.training-campaign-analyse', compact('trainingCampaign', 'userTrainings', 'countUserTrainingsGenerated', 'countUserTrainingsDone'));
    }

    public function stop($trainingCampaignId)
    {
        try {
            $trainingCampaign = TrainingCampaign::findOrFail($trainingCampaignId);
            $this->changeState($trainingCampaignId, 'Completed');
            return redirect()->back()->with('success', 'Campaign stopped successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function details($trainingCampaignId)
    {
        $trainingCampaign = TrainingCampaign::findOrFail($trainingCampaignId);
        $usersIds = UserTrainingCampaign::where('training_campaign_id', $trainingCampaign->id)->distinct()->pluck('user_id')->toArray();

        $users = User::whereIn('id', $usersIds)->get();

        return view('training-campaign.training-campaign-detail', compact('trainingCampaign', 'users'));
    }

    public function downloadDataCSV($trainingCampaignId)
    {
        try {
            $trainingCampaign = TrainingCampaign::findOrFail($trainingCampaignId);

            $csvData = $this->generateDataCSV([$trainingCampaign]);

            return $this->createCSVResponse($csvData, 'training_campaign_' . $trainingCampaignId);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function downloadAllDataCSV()
    {
        try {
            $trainingCampaigns = TrainingCampaign::all();

            $csvData = $this->generateDataCSV($trainingCampaigns);

            return $this->createCSVResponse($csvData, 'training_campaigns');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function duplicate($trainingCampaignId)
    {
        try {
            $trainingCampaign = TrainingCampaign::findOrFail($trainingCampaignId);

            $newTrainingCampaign = $trainingCampaign->replicate();

            $newTrainingCampaign->state = 'Draft';
            $newTrainingCampaign->title .= ' (Copy)';

            $newTrainingCampaign->save();

            return redirect()->back()->with('success', 'Campaign duplicated successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function destroy(TrainingCampaign $trainingCampaign)
    {
        $trainingCampaign->delete();

        return redirect()->back()->with('success', 'Campaign deleted successfully!');
    }

    public function option()
    {
        $prompts = TrainingPrompt::all();
        $users = User::where('role', 'User')->where('type', 'Fake')->get();

        return view('training-campaign.training-campaign-option', compact('prompts', 'users'));
    }

    private function generateTraining($llm, $prompt)
    {
        //     'content' => '{"questions":[{"question":"A","choices":["1","2","3","4","5"],"correct_answer":"1"},{"question":"A","choices":["1","2","3","4","5"],"correct_answer":"1"},{"question":"A","choices":["1","2","3","4","5"],"correct_answer":"1"},{"question":"A","choices":["1","2","3","4","5"],"correct_answer":"1"},{"question":"A","choices":["1","2","3","4","5"],"correct_answer":"1"}]}',

        // $response = [
        //     'error' => false,
        //     'status' => 200,
        //     'content' => 'Training content goes here',
        //     'timestamp' => now(),
        // ];
        // return $response;

        try {
            $response = $this->llmService->generateChatGPTHTTPPost($llm, $prompt);
            return [
                'error' => false,
                'status' => 200,
                'content' => $response,
                'timestamp' => now(),
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'code' => $e->getCode(),
                'status' => $e->getMessage(),
                'timestamp' => now(),
            ];
        }
    }

    private function generateDataCSV($trainingCampaigns)
    {
        $csvData = [
            ['ID', 'Training Campaign ID', 'Title', 'Description', 'Threat', 'LLM', 'Prompt', 'Type', 'State', 'User name', 'User surname', 'User gender', 'User dob', 'User company role', 'Training']
        ];

        $incrementalId = 1;

        foreach ($trainingCampaigns as $trainingCampaign) {
            $threat = Threat::where('id', $trainingCampaign->threat_id)->pluck('name')->first();
            $threat = $threat ?: null;

            $llm = LLM::where('id', $trainingCampaign->llm_id)->pluck('provider')->first();
            $llm = $llm ?: null;

            $usersTraining = UserTrainingCampaign::where('training_campaign_id', $trainingCampaign->id)->get();
            $usersTraining = $usersTraining ?: null;

            if ($usersTraining->isEmpty()) {
                $csvData[] = [
                    $incrementalId++,
                    $trainingCampaign->id,
                    $trainingCampaign->title,
                    $trainingCampaign->description,
                    $threat,
                    $llm,
                    $trainingCampaign->prompt,
                    $trainingCampaign->type,
                    $trainingCampaign->state,
                    '', // Empty User name
                    '', // Empty User surname
                    '', // Empty User gender
                    '', // Empty User dob
                    '', // Empty User company role
                    '', // Empty Training
                ];
            } else {
                foreach ($usersTraining as $userTraining) {

                    $user = User::find($userTraining->user_id);

                    $userName = $user->name ?? null;
                    $userSurname = $user->surname ?? null;
                    $userGender = $user->gender ?? null;
                    $userDob = $user->dob ?? null;
                    $userCompanyRole = $user->company_role ?? null;

                    $csvData[] = [
                        $incrementalId++,
                        $trainingCampaign->id,
                        $trainingCampaign->title,
                        $trainingCampaign->description,
                        $threat,
                        $llm,
                        $trainingCampaign->prompt,
                        $trainingCampaign->type,
                        $trainingCampaign->state,
                        $userName,
                        $userSurname,
                        $userGender,
                        $userDob,
                        $userCompanyRole,
                        $userTraining->training,
                    ];
                }
            }
        }
        return $csvData;
    }

    private function createCSVResponse($csvData, $fileNamePrefix)
    {
        $fileName = $fileNamePrefix . '_' . date('Y-m-d_H-i-s') . '.csv';
        $csvContent = '';

        foreach ($csvData as $row) {
            $csvContent .= implode(',', array_map(function ($value) {
                return is_null($value) ? '' : '"' . str_replace('"', '""', $value) . '"';
            }, $row)) . "\n";
        }

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ]);
    }
}
