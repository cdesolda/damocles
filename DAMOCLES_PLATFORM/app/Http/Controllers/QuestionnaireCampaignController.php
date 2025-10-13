<?php

namespace App\Http\Controllers;

use Exception;
use App\Jobs\SendQuestionnaireEmails;
use App\Models\Questionnaire;
use App\Models\QuestionnaireCampaign;
use App\Models\QuestionnaireQuestionnaireCampaign;
use App\Models\UserQuestionnaireCampaign;
use App\Models\UserQuestionnaireAnswer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionnaireCampaignController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'User') {
            $userQuestionnaireCampaigns = UserQuestionnaireCampaign::where('user_id', $user->id)
                ->with('questionnaireCampaign')
                ->get();

            $questionnairesCampaigns = $userQuestionnaireCampaigns->map(function ($userQuestionnaireCampaign) use ($user) {
                $campaign = $userQuestionnaireCampaign->questionnaireCampaign;

                // Controlla il numero di questionari nella campagna
                $totalQuestionnaires = DB::table('questionnaires_questionnaires_campaigns')
                    ->where('q_c_id', $campaign->id)
                    ->count();

                // Controlla quanti questionari ha completato l'utente
                $answeredQuestionnaires = DB::table('user_questionnaires_answers')
                    ->where('user_id', $user->id)
                    ->where('q_c_id', $campaign->id)
                    ->distinct('q_id')
                    ->count('q_id');

                if ($totalQuestionnaires == $answeredQuestionnaires) {
                    $userQuestionnaireCampaign->done = true;
                    $userQuestionnaireCampaign->save();
                }

                $campaign->user_done = $userQuestionnaireCampaign->done;
                $campaign->total_questionnaires = $totalQuestionnaires;
                $campaign->answered_questionnaires = $answeredQuestionnaires;

                return $campaign;
            })->filter(function ($campaign) {
                return in_array($campaign->state, ['Live', 'Completed']);
            });
        } elseif ($user->role === 'Evaluator') {
            $questionnairesCampaigns = QuestionnaireCampaign::where('evaluator_id', $user->id)->get();
        }

        return view('questionnaires-campaign.questionnaires-campaign', compact('questionnairesCampaigns'));
    }

    public function new()
    {
        $questionnaires = Questionnaire::all();

        return view('questionnaires-campaign.new-questionnaires-campaign', compact('questionnaires'));
    }

    public function questionnaires()
    {
        $questionnaires = Questionnaire::all();

        return view('questionnaires-campaign.questionnaires', compact('questionnaires'));
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'evaluatorId' => ['required', 'integer'],
            'state' => ['required', 'string', 'max:255'],
            'expirationDate' => ['required'],
        ]);

        $validatedData['expiration_date'] = $validatedData['expirationDate'];
        $validatedData['evaluator_id'] = $validatedData['evaluatorId'];

        $questionnaireCampaign = QuestionnaireCampaign::create($validatedData);

        return redirect()->route('questionnaire-campaign.questionnaires', ['questionnaireCampaign' => $questionnaireCampaign->id]);
    }

    public function questionnairesQuestionnaireCampaign($questionnaireCampaignId)
    {
        $questionnaireCampaign = QuestionnaireCampaign::findOrFail($questionnaireCampaignId);
        $questionnaires = Questionnaire::all();
        return view('questionnaires-campaign.questionnaires-questionnaire-campaign', compact('questionnaires', 'questionnaireCampaignId'));
    }

    public function saveQuestionnairesQuestionnaireCampaign(Request $request)
    {
        $validatedData = $request->validate([
            'questionnaireCampaignId' => ['required', 'integer'],
            'questionnairesIds' => ['required', 'string'],
        ]);

        $questionnaireCampaignId = $validatedData['questionnaireCampaignId'];
        $questionnaireIds = explode(',', $validatedData['questionnairesIds']);

        QuestionnaireQuestionnaireCampaign::where('q_c_id', $questionnaireCampaignId)->delete();

        foreach ($questionnaireIds as $questionnaireId) {
            QuestionnaireQuestionnaireCampaign::create([
                'q_id' => $questionnaireId,
                'q_c_id' => $questionnaireCampaignId,
            ]);
        }

        return redirect()->route('questionnaire-campaign.users', ['questionnaireCampaign' => $questionnaireCampaignId]);
    }

    public function users($questionnaireCampaignId)
    {
        $questionnaireCampaign = QuestionnaireCampaign::findOrFail($questionnaireCampaignId);
        $questionnaireQuestionnaireCampaign = QuestionnaireQuestionnaireCampaign::where('q_c_id', $questionnaireCampaignId)->firstOrFail();

        $users = User::where('role', 'User')->where('type', 'Real')->where('is_active', true)->where('is_accept', true)->get();

        return view('questionnaires-campaign.users-questionnaire', compact('questionnaireCampaignId', 'users'));
    }

    public function saveUsersQuestionnaireCampaign(Request $request)
    {
        $validatedData = $request->validate([
            'questionnaireCampaignId' => ['required', 'integer'],
            'usersIds' => ['required'],
        ]);

        $questionnaireCampaignId = $validatedData['questionnaireCampaignId'];
        $usersIds = explode(',', $validatedData['usersIds']);

        if (empty($usersIds) || empty($questionnaireCampaignId)) {
            return response()->json(['error' => 'Users or Questionnaire Campaign Id cannot be null'], 400);
        }

        foreach ($usersIds as $userId) {
            UserQuestionnaireCampaign::create([
                'user_id' => $userId,
                'questionnaire_campaign_id' => $questionnaireCampaignId,
                'done' => false,
            ]);
        }

        $this->changeState($questionnaireCampaignId, 'Ready');

        return redirect()->route('questionnaires-campaign.index')->with('success', 'Questionnaire campaign assigned successfully.');
    }

    public function changeState($questionnaireCampaignId, $state)
    {
        $questionnaireCampaign = QuestionnaireCampaign::findOrFail($questionnaireCampaignId);
        $questionnaireCampaign->state = $state;
        $questionnaireCampaign->save();

        if ($state != 'Draft' && $state != 'Ready') {

            if ($state == 'Live') {

                $userQuestionnaireCampaigns = UserQuestionnaireCampaign::where('questionnaire_campaign_id', $questionnaireCampaign->id)->get();
                $userIds = $userQuestionnaireCampaigns->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->get();

                DB::beginTransaction();
                try {
                    foreach ($users as $user) {
                        try {
                            // Dispatch the job
                            SendQuestionnaireEmails::dispatch($user, $questionnaireCampaign);
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

    public function joinQuestionnaireCampaign($questionnaireCampaignId)
    {
        $questionnaireCampaign = QuestionnaireCampaign::findOrFail($questionnaireCampaignId);

        $questionnaireIds = QuestionnaireQuestionnaireCampaign::where('q_c_id', $questionnaireCampaignId)
            ->pluck('q_id')
            ->toArray();

        $questionnaires = Questionnaire::whereIn('id', $questionnaireIds)->get();

        return view('questionnaires-campaign.questionnaire-campaign-join', compact('questionnaireCampaign', 'questionnaires'));
    }

    public function details($questionnaireCampaignId)
    {
        $questionnaireCampaign = QuestionnaireCampaign::findOrFail($questionnaireCampaignId);

        // Retrieve the IDs of the questionnaires associated with the questionnaire campaign
        $questionnaireIds = QuestionnaireQuestionnaireCampaign::where('q_c_id', $questionnaireCampaignId)
            ->distinct()
            ->pluck('q_id')
            ->toArray();

        // Retrieve the questionnaires using the questionnaire IDs
        $questionnaires = Questionnaire::whereIn('id', $questionnaireIds)->get();

        // Retrieve the IDs of the users associated with the questionnaire campaign
        $userIds = UserQuestionnaireCampaign::where('questionnaire_campaign_id', $questionnaireCampaignId)
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        // Retrieve the users using the user IDs
        $users = User::whereIn('id', $userIds)->get();

        return view('questionnaires-campaign.questionnaire-campaign-details', compact('questionnaireCampaign', 'questionnaires', 'users'));
    }

    public function analyse($questionnaireCampaignId)
    {
        $questionnaireCampaign = QuestionnaireCampaign::findOrFail($questionnaireCampaignId);

        // Retrieve the IDs of the questionnaire associated with the questionnaire campaign
        $questionnairesIds = QuestionnaireQuestionnaireCampaign::where('q_c_id', $questionnaireCampaignId)
            ->distinct()
            ->pluck('q_id')
            ->toArray();

        // Retrieve the questionnaires
        $questionnaires = Questionnaire::whereIn('id', $questionnairesIds)->get();

        // Retrieve the IDs of users associated with the questionnaire campaign
        $usersIds = UserQuestionnaireCampaign::where('questionnaire_campaign_id', $questionnaireCampaignId)
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        // Retrieve the users
        $users = User::whereIn('id', $usersIds)->get();

        // Retrieve the answers of users for the questionnaires
        $userAnswers = UserQuestionnaireAnswer::whereIn('q_id', $questionnairesIds)
            ->where('q_c_id', $questionnaireCampaignId)
            ->whereIn('user_id', $usersIds)
            ->get()
            ->groupBy(['user_id', 'q_id']);

        // Calculate the total number of answers given for each questionnaire
        $totalAnswersPerQuestionnaire = UserQuestionnaireAnswer::select('q_id', DB::raw('count(*) as total_answers'))
            ->where('q_c_id', $questionnaireCampaignId)
            ->whereIn('q_id', $questionnairesIds)
            ->whereIn('user_id', $usersIds)
            ->groupBy('q_id')
            ->pluck('total_answers', 'q_id')
            ->toArray();

        // Retrieve and process scales
        $answersWithQuestionnaireName = [];

        foreach ($userAnswers as $userId => $userQuestionnaires) {
            foreach ($userQuestionnaires as $qId => $answers) {
                $questionnaire = $questionnaires->firstWhere('id', $qId);

                if ($questionnaire) {
                    foreach ($answers as $answer) {
                        $answersWithQuestionnaireName[] = [
                            'id' => $answer->id,
                            'user_id' => $answer->user_id,
                            'q_id' => $answer->q_id,
                            'answer_id' => $answer->answer_id,
                            'questionnaire_name' => $questionnaire->name
                        ];
                    }
                }
            }
        }

        $allResponses = [];

        foreach ($answersWithQuestionnaireName as $answer) {
            $tableName = $answer['questionnaire_name'];
            $tableName = str_replace(' ', '_', $tableName);
            $answerId = $answer['answer_id'];
            $allResponses[$tableName][] = $answerId;
        }

        //Map each questionnaire to its controller
        $tableScores = [];
        $tableNameToControllerMap = [
            'Big_Five_Inventory' => 'App\Http\Controllers\BFI2XSController',
            'Susceptibility_to_Persuasion_II' => 'App\Http\Controllers\StPIIBController',
            'Trait_Emotional_Intelligence' => 'App\Http\Controllers\TEIQueSFController',
        ];

        $tableNameAverages = [];
        foreach ($allResponses as $tableName => $answerIds) {
            $averageScores = [];

            if (!isset($tableNameToControllerMap[$tableName])) {
                continue;
            }

            $controllerClass = $tableNameToControllerMap[$tableName];
            if (class_exists($controllerClass)) {
                $controllerInstance = app($controllerClass);

                $scores = [];
                foreach ($answerIds as $id) {
                    $result = $controllerInstance->calculateScales($id);

                    foreach ($result as $scale => $value) {
                        if (!isset($scores[$scale])) {
                            $scores[$scale] = [];
                        }
                        $scores[$scale][] = $value;
                    }
                }

                // Average of the scores
                foreach ($scores as $scale => $values) {
                    $averageScores[$scale] = array_sum($values) / count($values);
                }

                $tableNameAverages[$tableName] = $averageScores;
            } else {
                echo "Controller for $tableName not found.\n";
            }
        }


        return view('questionnaires-campaign.questionnaire-campaign-analyse', compact(
            'questionnaireCampaign',
            'questionnaires',
            'users',
            'userAnswers',
            'totalAnswersPerQuestionnaire',
            'tableNameAverages'
        ));
    }

    public function stop($questionnaireCampaignId)
    {
        try {
            $questionnaireCampaign = QuestionnaireCampaign::findOrFail($questionnaireCampaignId);
            $this->changeState($questionnaireCampaignId, 'Completed');
            return redirect()->back()->with('success', 'Campaign stopped successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function deleteAnswer(Request $request)
    {
        $validatedData = $request->validate([
            'answerId' => ['required', 'integer'],
        ]);

        $userAnswer = UserQuestionnaireAnswer::findOrFail($validatedData['answerId']);

        $questionnaireName = $userAnswer->questionnaire->name;
        $answerId = $userAnswer->answer_id;

        // Convert the questionnaire name to uppercase
        $tableName = $questionnaireName . 's';

        // Delete the row from the specified table
        DB::table($tableName)->where('id', $answerId)->delete();

        $userAnswer->delete();
        $userAnswer->delete();

        return redirect()->back()->with('success', 'Answer deleted successfully.');
    }

    public function duplicate($questionnaireCampaignId)
    {
        try {
            $questionnaireCampaign = QuestionnaireCampaign::findOrFail($questionnaireCampaignId);

            $newQuestionnaireCampaign = $questionnaireCampaign->replicate();

            $newQuestionnaireCampaign->state = 'Draft';
            $newQuestionnaireCampaign->title .= ' (Copy)';

            $newQuestionnaireCampaign->save();

            return redirect()->back()->with('success', 'Campaign duplicated successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function destroy(QuestionnaireCampaign $questionnaireCampaign)
    {
        $questionnaireCampaign->delete();

        return redirect()->back()->with('success', 'Campaign deleted successfully!');
    }
}
