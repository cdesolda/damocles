<?php

namespace App\Http\Controllers;

use App\Models\DigitalTwinsCampaign;
use App\Models\DigitalTwinsResult;
use App\Models\HumanFactor;
use App\Models\LLM;
use App\Models\PhishingTopic;
use App\Models\PhishingEmail;
use App\Models\PhishingEmailGroup;
use App\Models\Threat;
use App\Models\User;
use App\Models\UserHfThreat;
use App\Models\PhishingPersuasion;
use App\Models\PhishingEmotionalTrigger;
use App\Services\LLMService;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class DigitalTwinCampaignController extends Controller
{
    protected $llmService;

    public function __construct(LLMService $llmService)
    {
        $this->llmService = $llmService;
    }

    public function index()
    {
        $user = auth()->user();
        $digitalTwinsCampaigns = DigitalTwinsCampaign::where('evaluator_id', $user->id)->get();
        return view('digital-twin.digital-twins', compact('digitalTwinsCampaigns'));
    }

    public function new()
    {
        $threats = Threat::all();

        return view('digital-twin.new-digital-twins-campaign', compact('threats')); #,'demographicAttributes', 'humanFactors'));
    }

    public function defineUserPrompt(Request $request)
    {
        $validatedData = $request->session()->get('validatedData', []);

        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
                'threatId' => ['required', 'integer'],
                'evaluatorId' => ['required', 'integer'],
            ]);
            $validatedData['threat_id'] = $validatedData['threatId'];
            $validatedData['evaluator_id'] = $validatedData['evaluatorId'];
            unset($validatedData['threatId'], $validatedData['evaluatorId']);

            $request->session()->put('validatedData', $validatedData);
        }

        $demographicAttributes = User::getdemographicFields();
        $humanFactors = HumanFactor::all();
        $llms = LLM::all();

        return view('digital-twin.new-digital-twins-campaign-define-user-prompt', compact('validatedData', 'demographicAttributes', 'humanFactors', 'llms'));
    }

    public function defineAttachPrompt(Request $request)
    {

        $validatedData = $request->session()->get('validatedData');

        if ($request->isMethod('post')) {
            $newData = $request->validate([
                'demographics' => ['nullable'],
                'humanfactors' => ['nullable'],
                'typeOfprompt' => ['required', 'string', 'max:255'],
                'prompt' => ['required'],
                'llmId' => ['required', 'integer'],
            ]);

            $validatedData = array_merge($validatedData, [
                'demographics' => $newData['demographics'] ?? null,
                'human_factors' => $newData['humanfactors'] ?? null,
                'user_prompt_type' => $newData['typeOfprompt'],
                'user_prompt' => $newData['prompt'],
                'llm_id' => $newData['llmId'],
            ]);

            $request->session()->put('validatedData', $validatedData);
        }

        $threatName = Threat::where('id', $validatedData['threat_id'])->value('name');

        return view('digital-twin.new-digital-twins-campaign-define-threat-prompt', compact('validatedData', 'threatName'));
    }

    public function chooseEmail(Request $request)
    {

        $validatedData = $request->session()->get('validatedData');

        if ($request->isMethod('post')) {
            $newData = $request->validate([
                'prompt' => ['required'],
                'typeOfprompt' => ['required', 'string', 'max:255'],
            ]);
            $validatedData = array_merge($validatedData, [
                'threat_prompt' => $newData['prompt'],
                'threat_prompt_type' => $newData['typeOfprompt'],
            ]);

            $request->session()->put('validatedData', $validatedData);
        }

        $emails = PhishingEmail::all()->map(function ($email) {
            // Decode emotionalTriggers and persuasions IDs
            $emotionalTriggersIds = explode(',', $email->emotional_triggers);
            $persuasionsIds = explode(',', $email->persuasions);

            // Fetch corresponding names
            $email->emotional_triggers = PhishingEmotionalTrigger::whereIn('id', $emotionalTriggersIds)->pluck('description');
            $email->persuasions = PhishingPersuasion::whereIn('id', $persuasionsIds)->pluck('description');
            $email->topic = PhishingTopic::find($email->topic_id)->description ?? 'Unknown Topic';

            return $email;
        });

        $topics = PhishingTopic::all();
        $persuasions = PhishingPersuasion::all();
        $emotionalTriggers = PhishingEmotionalTrigger::all();
        $groups = PhishingEmailGroup::all();

        return view('digital-twin.new-digital-twins-campaign-choose-email', compact(
            'emails',
            'validatedData',
            'topics',
            'persuasions',
            'emotionalTriggers',
            'groups'
        ));
    }

    public function simulation(Request $request)
    {
        $validatedData = $request->session()->get('validatedData');

        if ($request->isMethod('post')) {
            $newData = $request->validate([
                'emailsIds' => 'required',
            ]);
            $validatedData = array_merge($validatedData, [
                'emails_ids' => $newData['emailsIds'],
            ]);

            $request->session()->put('validatedData', $validatedData);
        }

        $users = User::where('role', 'User')->where('type', 'Fake')->get();
        $humanFactors = HumanFactor::all();
        return view('digital-twin.new-digital-twins-campaign-simulation', compact('users', 'humanFactors'));
    }

    public function simulationResult(Request $request)
    {
        $validatedData = $request->session()->get('validatedData');

        if ($request->isMethod('post')) {
            $newData = $request->validate([
                'selectedUserIds' => 'required',
            ]);
            $validatedData = array_merge($validatedData, [
                'usersIds' => $newData['selectedUserIds'],
            ]);

            $request->session()->put('validatedData', $validatedData);
        }

        $emailIds = explode(',', $validatedData['emails_ids']);

        $customizedAttachPrompts = $this->generateThreatPrompts($emailIds, $validatedData['threat_prompt']);

        $demographics = explode(',', $validatedData['demographics']);
        $humanfactorsIds = explode(',', $validatedData['human_factors']);
        $humanfactors = HumanFactor::whereIn('id', $humanfactorsIds)->get();

        // Generate new prompts for each user
        $search = [];
        foreach ($demographics as $demo) {
            $search[] = "[" . strtoupper($demo . "_value") . "]";
        }
        foreach ($humanfactors as $humanfactor) {
            $search[] = "[" . strtoupper($humanfactor->name . "_value") . "]";
        }

        $selectedUsers = json_decode($validatedData['usersIds'], true);
        $userIds = collect($selectedUsers)->pluck('id')->toArray();

        $users = User::where('type', 'Fake')->whereIn('id', $userIds)->get();

        $customizedUserPrompts = [];

        foreach ($users as $fakeUser) {
            $replace = [];
            foreach ($demographics as $demo) {
                $replace[] = $fakeUser->$demo;
            }
            $userData = collect($selectedUsers)->firstWhere('id', $fakeUser->id);

            foreach ($humanfactors as $humanfactor) {
                $severityLevel = 'not measured';
                if ($userData && isset($userData['humanFactors'])) {
                    foreach ($userData['humanFactors'] as $hf) {
                        if ($hf['humanFactor'] == $humanfactor->id) {
                            $severityLevel = $hf['education'] . '  out of ' . $humanfactor->likert_scales;
                            break;
                        }
                    }
                }
                $replace[] = $severityLevel;
            }

            $customizedUserPrompt = str_replace($search, $replace, $validatedData['user_prompt']);

            $customizedAttachPromptsForUser = $customizedAttachPrompts;

            foreach ($customizedAttachPromptsForUser as &$customizedAttachPrompt) {
                $customizedAttachPrompt['customized_prompt'] = str_replace(
                    ['-name', '-surname'],
                    [$fakeUser->name, $fakeUser->surname],
                    $customizedAttachPrompt['customized_prompt']
                );

                $finalPrompt = $customizedUserPrompt . $customizedAttachPrompt['customized_prompt'] .
                    'Generate the response strictly in the following JSON format without any extra text:
                {
                    "opened": "email opened (YES,NO)",
                    "opened_explanation": "explain why the user opened or did not open the email",
                    "clicked": "link of the email clicked (YES,NO)",
                    "clicked_explanation": "explain why the user clicked or did not click the link"
                }';

                $customizedUserPrompts[] = [
                    'user_id' => $fakeUser->id,
                    'email_id' => $customizedAttachPrompt['email_id'],
                    'customized_prompt' => $finalPrompt,
                ];
            }
        }

        $llm = LLM::findOrFail($validatedData['llm_id']);
        $results = $this->generateUserResponses($llm, $customizedUserPrompts);

        foreach ($users as $user) {
            $user->emails = collect($results)
                ->where('user_id', $user->id)
                ->map(function ($result) {
                    $email = PhishingEmail::find($result['email_id']);
                    return [
                        'email' => $email ? $email :  (object) ['subject' => 'Phishing Email'],
                        'opened' => !is_null($result['opened_result']),
                        'clicked' => !is_null($result['clicked_result']),
                        'opened_explanation' => $result['opened_explanation'] ? $result['opened_explanation'] : null,
                        'clicked_explanation' => $result['clicked_explanation'] ? $result['clicked_explanation'] : null
                    ];
                })->toArray();
        }

        return view('digital-twin.new-digital-twins-campaign-simulation-result', compact('users'));
    }

    public function chooseUsers(Request $request)
    {
        $validatedData = $request->session()->get('validatedData');

        $users = User::where('role', 'User')->where('type', 'Real')->where('is_active', true)->where('is_accept', true)->get();

        return view('digital-twin.new-digital-twins-campaign-choose-users', compact('users', 'validatedData'));
    }

    public function finalizeCreation(Request $request)
    {
        $validatedData = $request->session()->get('validatedData');
        if ($request->isMethod('post')) {
            $newData = $request->validate([
                'usersIds' => 'required',
            ]);
            $validatedData = array_merge($validatedData, [
                'usersIds' => $newData['usersIds'],
            ]);

            $request->session()->put('validatedData', $validatedData);
        }

        $dwCampaign = DigitalTwinsCampaign::create(array_diff_key($validatedData, ['usersIds' => '']));

        $usersIds = explode(',', $validatedData['usersIds']);
        $emailsIds = explode(',', $validatedData['emails_ids']);

        $request->session()->forget('validatedData');

        if (empty($usersIds)) {
            return response()->json(['error' => 'Users cannot be null'], 400);
        }


        foreach ($usersIds as $userId) {
            foreach ($emailsIds as $emailId) {
                DigitalTwinsResult::create([
                    'user_id' => $userId,
                    'email_id' => $emailId,
                    'digital_twins_campaign_id' => $dwCampaign->id,
                ]);
            }
        }

        return redirect(route('digital-twins.executeCampaign', ['digitalTwinsCampaign' => $dwCampaign->id]));
    }

    public function details($digitalTwinsCampaignId)
    {
        $digitalTwinsCampaign = DigitalTwinsCampaign::findOrFail($digitalTwinsCampaignId);

        $demographics = explode(',', $digitalTwinsCampaign->demographics);
        $humanfactorsIds = explode(',', $digitalTwinsCampaign->human_factors);

        $humanfactors = HumanFactor::whereIn('id', $humanfactorsIds)->get();

        $emails = PhishingEmail::whereIn('id', explode(',', $digitalTwinsCampaign->emails_ids))->get()->map(function ($email) {
            // Decode emotionalTriggers and persuasions IDs
            $emotionalTriggersIds = explode(',', $email->emotional_triggers);
            $persuasionsIds = explode(',', $email->persuasions);

            // Fetch corresponding names
            $email->emotional_triggers = PhishingEmotionalTrigger::whereIn('id', $emotionalTriggersIds)->pluck('description');
            $email->persuasions = PhishingPersuasion::whereIn('id', $persuasionsIds)->pluck('description');
            $email->topic = PhishingTopic::find($email->topic_id)->description ?? 'Unknown Topic';

            return $email;
        });

        // Retrieve the IDs of the users associated with the campaign
        $userIds = DigitalTwinsResult::where('digital_twins_campaign_id', $digitalTwinsCampaignId)
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        // Retrieve the users using the user IDs
        $users = User::select('users.*', 'digital_twins_results.state')
            ->join('digital_twins_results', 'users.id', '=', 'digital_twins_results.user_id')
            ->where('digital_twins_results.digital_twins_campaign_id', $digitalTwinsCampaignId)
            ->distinct()
            ->get();

        return view('digital-twin.digital-twins-campaign-detail', compact('digitalTwinsCampaign', 'emails', 'demographics', 'humanfactors', 'users'));
    }

    public function executeDigitalTwinCampaign($digitalTwinsCampaignId)
    {
        $digitalTwinsCampaign = DigitalTwinsCampaign::findOrFail($digitalTwinsCampaignId);

        // Retrieve the IDs of the users associated with the campaign
        $userIds = DigitalTwinsResult::where('digital_twins_campaign_id', $digitalTwinsCampaignId)
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        // Retrieve the users using the user IDs
        $users = User::select('users.*', 'digital_twins_results.state')
            ->join('digital_twins_results', 'users.id', '=', 'digital_twins_results.user_id')
            ->where('digital_twins_results.digital_twins_campaign_id', $digitalTwinsCampaignId)
            ->distinct()
            ->get();

        return view('digital-twin.execute-digital-twins-campaign', compact('digitalTwinsCampaign', 'users'));
    }

    public function addUsers(Request $request, $digitalTwinsCampaignId)
    {
        $digitalTwinsCampaign = DigitalTwinsCampaign::findOrFail($digitalTwinsCampaignId);

        $users = User::select('users.*')
            ->leftJoin('digital_twins_results', function ($join) use ($digitalTwinsCampaignId) {
                $join->on('users.id', '=', 'digital_twins_results.user_id')
                    ->where('digital_twins_results.digital_twins_campaign_id', '=', $digitalTwinsCampaignId);
            })
            ->where('role', 'User')
            ->where('type', 'Real')
            ->where(function ($query) use ($digitalTwinsCampaignId) {
                $query->whereNull('digital_twins_results.user_id')
                    ->orWhere('digital_twins_results.digital_twins_campaign_id', '!=', $digitalTwinsCampaignId);
            })
            ->distinct()
            ->get();

        $submitRoute = route('digital-twins.executeDigitalTwinCampaign', [$digitalTwinsCampaignId]);

        return view('digital-twin.add-users', compact('digitalTwinsCampaign', 'users', 'submitRoute'));
    }

    public function finalizeAddUsers(Request $request, $digitalTwinsCampaignId)
    {
        $usersIds = null;
        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'usersIds' => 'required',
            ]);
            $usersIds = $validatedData['usersIds'];
            $usersIds = explode(',', $usersIds);
        }

        if (empty($usersIds)) {
            return response()->json(['error' => 'Users cannot be null'], 400);
        }

        $digitalTwinsCampaign = DigitalTwinsCampaign::find($digitalTwinsCampaignId);

        if (!$digitalTwinsCampaign) {
            return response()->json(['error' => 'Digital Twin Campaign not found'], 404);
        }

        $emailsIds = explode(',', $digitalTwinsCampaign->emails_ids);

        foreach ($usersIds as $userId) {
            foreach ($emailsIds as $emailId) {
                DigitalTwinsResult::create([
                    'user_id' => $userId,
                    'email_id' => $emailId,
                    'digital_twins_campaign_id' => $digitalTwinsCampaignId,
                ]);
            }
        }

        return redirect(route('digital-twins.executeDigitalTwinCampaign', ['digitalTwinsCampaign' => $digitalTwinsCampaignId]));
    }

    public function destroy(DigitalTwinsCampaign $digitalTwinsCampaign)
    {
        $digitalTwinsCampaign->delete();

        return redirect()->back()->with('success', 'Campaign deleted successfully!');
    }
    public function analyseUser(Request $request, $digitalTwinsCampaignId, $userId)
    {
        $digitalTwinsCampaign = DigitalTwinsCampaign::findOrFail($digitalTwinsCampaignId);

        $digitalTwinsResults = DigitalTwinsResult::where('digital_twins_campaign_id', $digitalTwinsCampaign->id)
            ->whereNotNull('state')
            ->where('user_id', $userId)
            ->get();

        $emailsIds = explode(',', $digitalTwinsCampaign->emails_ids);
        $emailsInvolved = PhishingEmail::whereIn('id', $emailsIds)->get();

        // Collect the user data and their click status
        $users = User::whereIn('id', $digitalTwinsResults->pluck('user_id')->unique())->get()
            ->map(function ($user) use ($digitalTwinsResults, $emailsInvolved) {
                $userEmails = $digitalTwinsResults->where('user_id', $user->id);
                // Map emails: If no emails exist, return a default email (Email deleted)
                $user->emails = $emailsInvolved->map(function ($emailData) use ($userEmails) {
                    $matchingEmail = $userEmails->firstWhere('email_id', $emailData->id);
                    return [
                        'email' => $emailData, // Actual email data
                        'opened' => $matchingEmail ? $matchingEmail->opened : null,
                        'clicked' => $matchingEmail ? $matchingEmail->clicked : null,
                        'opened_explanation' => $matchingEmail ? $matchingEmail->opened_explanation : null,
                        'clicked_explanation' => $matchingEmail ? $matchingEmail->clicked_explanation : null,
                        'updated_at' => $matchingEmail ? $matchingEmail->updated_at : null,
                    ];
                });

                return $user;
            });
        return view('digital-twin.digital-twins-campaign-analyse-user', compact('digitalTwinsCampaign', 'users'));
    }

    public function analyse(Request $request, $digitalTwinsCampaignId)
    {
        $digitalTwinsCampaign = DigitalTwinsCampaign::findOrFail($digitalTwinsCampaignId);

        // Collect the users and their click status for the emails in this campaign
        $digitalTwinsResults = DigitalTwinsResult::where('digital_twins_campaign_id', $digitalTwinsCampaign->id)
            ->whereNotNull('state')
            ->get();

        $emailOpened = DigitalTwinsResult::where('digital_twins_campaign_id', $digitalTwinsCampaign->id)
            ->whereNotNull('opened')
            ->get();
        $emailNotOpened = DigitalTwinsResult::where('digital_twins_campaign_id', $digitalTwinsCampaign->id)
            ->whereNotNull('state')
            ->whereNull('opened')
            ->get();

        $emailClicked = DigitalTwinsResult::where('digital_twins_campaign_id', $digitalTwinsCampaign->id)
            ->whereNotNull('clicked')
            ->get();

        $emailNotClicked = DigitalTwinsResult::where('digital_twins_campaign_id', $digitalTwinsCampaign->id)
            ->whereNotNull('state')
            ->whereNull('clicked')
            ->get();

        // Count male, female and other users who opened the email phishing
        $openedMaleCount = User::whereIn('id', $emailOpened->pluck('user_id'))
            ->where('gender', 'Male')
            ->count();

        $openedFemaleCount = User::whereIn('id', $emailOpened->pluck('user_id'))
            ->where('gender', 'Female')
            ->count();

        $openedOtherCount = User::whereIn('id', $emailOpened->pluck('user_id'))
            ->where('gender', 'Other')
            ->count();

        // Count male, female and other users who clicked the phishing link
        $clickedMaleCount = User::whereIn('id', $emailClicked->pluck('user_id'))
            ->where('gender', 'Male')
            ->count();

        $clickedFemaleCount = User::whereIn('id', $emailClicked->pluck('user_id'))
            ->where('gender', 'Female')
            ->count();

        $clickedOtherCount = User::whereIn('id', $emailClicked->pluck('user_id'))
            ->where('gender', 'Other')
            ->count();

        $emailsIds = explode(',', $digitalTwinsCampaign->emails_ids);
        $emailsInvolved = PhishingEmail::whereIn('id', $emailsIds)->get();

        // Collect the user data and their click status
        $users = User::whereIn('id', $digitalTwinsResults->pluck('user_id')->unique())->get()
            ->map(function ($user) use ($digitalTwinsResults, $emailsInvolved) {
                $userEmails = $digitalTwinsResults->where('user_id', $user->id);
                $user->emails = $emailsInvolved->map(function ($emailData) use ($userEmails) {
                    $matchingEmail = $userEmails->firstWhere('email_id', $emailData->id);
                    return [
                        'email' => $emailData, // Actual email data
                        'opened' => $matchingEmail ? $matchingEmail->opened : null,
                        'clicked' => $matchingEmail ? $matchingEmail->clicked : null,
                        'opened_explanation' => $matchingEmail ? $matchingEmail->opened_explanation : null,
                        'clicked_explanation' => $matchingEmail ? $matchingEmail->clicked_explanation : null,
                        'updated_at' => $matchingEmail ? $matchingEmail->updated_at : null,
                    ];
                });

                return $user;
            });
        // Collect the email data and their click status
        $usersInvolved = User::whereIn('id', $digitalTwinsResults->pluck('user_id')->unique())->get();
        $emails = $emailsInvolved->map(function ($emailData) use ($digitalTwinsResults, $usersInvolved) {
            $emailUsers = $usersInvolved->map(function ($user) use ($emailData, $digitalTwinsResults) {
                $userEmail = $digitalTwinsResults->where('user_id', $user->id)->where('email_id', $emailData->id)->first();

                return [
                    'user' => $user, // Store full user object
                    'opened' => $userEmail ? $userEmail->opened : null,
                    'clicked' => $userEmail ? $userEmail->clicked : null,
                    'opened_explanation' => $userEmail ? $userEmail->opened_explanation : null,
                    'clicked_explanation' => $userEmail ? $userEmail->clicked_explanation : null,
                    'updated_at' => $userEmail ? $userEmail->updated_at : null,
                ];
            });
            $emailData->users = $emailUsers;

            return $emailData;
        });

        $filter = $request->query('filter');

        return view('digital-twin.digital-twins-campaign-analyse', compact('filter', 'digitalTwinsCampaign', 'users', 'emails', 'digitalTwinsResults', 'emailOpened', 'emailNotOpened', 'emailClicked', 'emailNotClicked', 'openedMaleCount', 'openedFemaleCount', 'openedOtherCount', 'clickedMaleCount', 'clickedFemaleCount', 'clickedOtherCount'));
    }

    public function downloadDataCSV($digitalTwinsCampaignId)
    {
        $digitalTwinsCampaign = DigitalTwinsCampaign::findOrFail($digitalTwinsCampaignId);
        try {
            $csvData = $this->generateDataCSV([$digitalTwinsCampaign]);
            return $this->createCSVResponse($csvData, 'digital_twins_campaign_' . $digitalTwinsCampaignId);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    private function generateDataCSV($digitalTwinsCampaigns)
    {
        $csvData = [
            ['ID', 'Campaign ID', 'Title', 'Description', 'User prompt type', 'User Prompt', 'Threat', 'Threat prompt type', 'Threat Prompt', 'Subject', 'Body', 'User name', 'User surname', 'User gender', 'User dob', 'User company role', 'State', 'Opened', 'Opened explanation', 'Clicked', 'Clicked explanation']
        ];

        $incrementalId = 1;

        foreach ($digitalTwinsCampaigns as $digitalTwinsCampaign) {
            $threat = Threat::where('id', $digitalTwinsCampaign->threat_id)->pluck('name')->first();
            $threat = $threat ?: null;

            $emailsIds = explode(',', $digitalTwinsCampaign->emails_ids);
            $generatedEmail = PhishingEmail::whereIn('id', $emailsIds)
                ->get(['id as email_id', 'subject', 'body']);

            if ($generatedEmail->isEmpty()) {
                $csvData[] = [
                    $incrementalId++,
                    $digitalTwinsCampaign->id,
                    $digitalTwinsCampaign->title,
                    $digitalTwinsCampaign->description,
                    $digitalTwinsCampaign->user_prompt_type,
                    $digitalTwinsCampaign->user_prompt,
                    $threat,
                    $digitalTwinsCampaign->threat_prompt_type,
                    $digitalTwinsCampaign->threat_prompt,
                    '', // Empty Subject
                    '', // Empty Body
                    '', // Empty User name
                    '', // Empty User surname
                    '', // Empty User gender
                    '', // Empty User dob
                    '', // Empty User company role
                    '', // Empty State                 
                    '', // Empty Opened
                    '', // Empty Opened explanation
                    '', // Empty Clicked
                    '', // Empty Clicked explanation
                ];
            } else {
                foreach ($generatedEmail as $email) {
                    $digitalTwinsResults = DigitalTwinsResult::where('digital_twins_campaign_id', $digitalTwinsCampaign->id)
                        ->where('email_id', $email->email_id)->get();
                    if ($digitalTwinsResults->isEmpty()) {
                        $csvData[] = [
                            $incrementalId++,
                            $digitalTwinsCampaign->id,
                            $digitalTwinsCampaign->title,
                            $digitalTwinsCampaign->description,
                            $digitalTwinsCampaign->user_prompt_type,
                            '"' . str_replace(["\r", "\n"], ' ', $digitalTwinsCampaign->user_prompt) . '"',
                            $threat,
                            $digitalTwinsCampaign->threat_prompt_type,
                            '"' . str_replace(["\r", "\n"], ' ', $digitalTwinsCampaign->threat_prompt) . '"',
                            $email->subject,
                            $email->body,
                            '', // Empty User name
                            '', // Empty User surname
                            '', // Empty User gender
                            '', // Empty User dob
                            '', // Empty User company role
                            '', // Empty State 
                            '', // Empty Opened
                            '', // Empty Opened explanation
                            '', // Empty Clicked
                            '', // Empty Clicked explanation
                        ];
                    } else {
                        foreach ($digitalTwinsResults as $digitalTwinsResult) {

                            $user = User::find($digitalTwinsResult->user_id);

                            $emailSubject = $email->subject ?: null;
                            $emailBody = $email->body ?: null;
                            $userName = $user->name ?? null;
                            $userSurname = $user->surname ?? null;
                            $userGender = $user->gender ?? null;
                            $userDob = $user->dob ?? null;
                            $userCompanyRole = $user->company_role ?? null;
                            $state = $digitalTwinsResult->state ? "executed" : "not executed";
                            $opened = $digitalTwinsResult->opened ?: null;
                            $opened_explanation = $digitalTwinsResult->opened_explanation ?: null;
                            $clicked = $digitalTwinsResult->clicked ?: null;
                            $clicked_explanation = $digitalTwinsResult->clicked_explanation ?: null;

                            $csvData[] = [
                                $incrementalId++,
                                $digitalTwinsCampaign->id,
                                $digitalTwinsCampaign->title,
                                $digitalTwinsCampaign->description,
                                $digitalTwinsCampaign->user_prompt_type,
                                '"' . str_replace(["\r", "\n"], ' ', $digitalTwinsCampaign->user_prompt) . '"',
                                $threat,
                                $digitalTwinsCampaign->threat_prompt_type,
                                '"' . str_replace(["\r", "\n"], ' ', $digitalTwinsCampaign->threat_prompt) . '"',
                                $emailSubject,
                                $emailBody,
                                $userName,
                                $userSurname,
                                $userGender,
                                $userDob,
                                $userCompanyRole,
                                $state,
                                $opened,
                                $opened_explanation,
                                $clicked,
                                $clicked_explanation,
                            ];
                        }
                    }
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

    public function executeCampaign($dwCampaignId)
    {
        $campaign = DigitalTwinsCampaign::find($dwCampaignId);

        if (!$campaign) {
            throw new \Exception('Associated campaign not found.');
        }

        $llm = LLM::findOrFail($campaign->llm_id);

        $originalUserPrompt = $campaign->user_prompt;

        $users = DigitalTwinsResult::where('digital_twins_campaign_id', $campaign->id)
            ->whereNull('state')
            ->with('user')
            ->get();

        // Generate new prompts for the attach
        $originalAttachPrompt = $campaign->threat_prompt;

        $emailIds = explode(',', $campaign->emails_ids);

        $customizedAttachPrompts = $this->generateThreatPrompts($emailIds, $originalAttachPrompt);

        $demographics = explode(',', $campaign->demographics);
        $humanfactorsIds = explode(',', $campaign->human_factors);
        $humanfactors = HumanFactor::whereIn('id', $humanfactorsIds)->get();

        // Generate new prompts for each user
        $search = [];
        foreach ($demographics as $demo) {
            $search[] = "[" . strtoupper($demo . "_value") . "]";
        }
        foreach ($humanfactors as $humanfactor) {
            $search[] = "[" . strtoupper($humanfactor->name . "_value") . "]";
        }

        $customizedUserPrompts = $this->generateUserPrompts(
            $users,
            $originalUserPrompt,
            $customizedAttachPrompts,
            $search,
            $demographics,
            $humanfactors
        );


        $results = $this->generateUserResponses($llm, $customizedUserPrompts);

        foreach ($results as $result) {
            DigitalTwinsResult::where('user_id', $result['user_id'])
                ->where('digital_twins_campaign_id', $dwCampaignId)
                ->where('email_id', $result['email_id'])
                ->update([
                    'opened' => $result['opened_result'],
                    'clicked' => $result['clicked_result'],
                    'opened_explanation' => $result['opened_explanation'],
                    'clicked_explanation' => $result['clicked_explanation'],
                    'state' => true,
                ]);
        }

        return redirect(route('digital-twins.executeDigitalTwinCampaign', ['digitalTwinsCampaign' => $campaign->id]));
    }

    public function generateThreatPrompts(array $emailIds, string $originalAttachPrompt)
    {
        $customizedAttachPrompts = [];

        foreach ($emailIds as $email_id) {
            $email = PhishingEmail::find(trim($email_id));

            if ($email) {
                $customizedAttachPrompts[] = [
                    'email_id' => $email->id,
                    'customized_prompt' => str_replace('[EMAIL CONTENT]', $email->body, $originalAttachPrompt),
                ];
            }
        }
        return $customizedAttachPrompts;
    }

    function generateUserPrompts($users, $originalUserPrompt, $customizedAttachPrompts, $search, $demographics, $humanfactors)
    {
        $customizedUserPrompts = [];

        foreach ($users as $result) {
            $user = $result->user;

            $replace = [];
            foreach ($demographics as $demo) {
                $replace[] = $user->$demo;
            }
            foreach ($humanfactors as $humanfactor) {
                $severityLevel  = UserHfThreat::getSeverityLevel($user->id, $humanfactor->id);
                $replace[] = ($severityLevel !== 'not measured')
                    ? $severityLevel . ' out of ' . $humanfactor->likert_scales
                    : $severityLevel;
            }

            $customizedUserPrompt = str_replace($search, $replace, $originalUserPrompt);
            $customizedAttachPromptsForUser = $customizedAttachPrompts;

            $selectedAttachPrompt = null;
            foreach ($customizedAttachPrompts as $attachPrompt) {
                if ($attachPrompt['email_id'] == $result->email_id) {
                    $selectedAttachPrompt = $attachPrompt;
                    break;
                }
            }

            if ($selectedAttachPrompt) {
                $selectedAttachPrompt['customized_prompt'] = str_replace(
                    ['-name', '-surname'],
                    [$user->name ?? 'John', $user->surname ?? 'Doe'],
                    $selectedAttachPrompt['customized_prompt']
                );

                // Construct the final prompt
                $finalPrompt = $customizedUserPrompt . $selectedAttachPrompt['customized_prompt'] .
                    'Generate the response strictly in the following JSON format without any extra text:
                    {
                        "opened": "email opened (YES,NO)",
                        "opened_explanation": "explain why the user opened or did not open the email",
                        "clicked": "link of the email clicked (YES,NO)",
                        "clicked_explanation": "explain why the user clicked or did not click the link"
                    }';

                $customizedUserPrompts[] = [
                    'user_id' => $user->id,
                    'email_id' => $selectedAttachPrompt['email_id'],
                    'customized_prompt' => $finalPrompt,
                ];
            }
        }
        return $customizedUserPrompts;
    }

    function generateUserResponses($llm, $customizedUserPrompts, $maxRetries = 5): array
    {
        $results = [];
        foreach ($customizedUserPrompts as $userPrompt) {

            $userId = $userPrompt['user_id'];
            $emailId = $userPrompt['email_id'];
            $finalPrompt = $userPrompt['customized_prompt'];
            $errorCount = 0;

            while ($errorCount < $maxRetries) {
                try {

                    $tempContent = '{
                        "opened": "YES",
                        "opened_explanation": "yes because ...",
                        "clicked": "NO",
                        "clicked_explanation": "no because ..."
                    }';
                    $tempContent = $this->llmService->generateChatGPTHTTPPost($llm, $finalPrompt);
                    $cleanContent = $this->llmService->extractJson($tempContent);
                    $decodedContent = json_decode($cleanContent, true);

                    if (
                        json_last_error() === JSON_ERROR_NONE &&
                        isset($decodedContent['opened']) &&
                        isset($decodedContent['clicked']) &&
                        isset($decodedContent['opened_explanation']) &&
                        isset($decodedContent['clicked_explanation'])
                    ) {
                        // Update responses
                        $openedResult = ($decodedContent['opened'] === 'YES') ? now()->format('Y-m-d H:i:s') : null;
                        $clickedResult = ($decodedContent['clicked'] === 'YES') ? now()->format('Y-m-d H:i:s') : null;
                        $results[] = [
                            'user_id' => $userId,
                            'email_id' => $emailId,
                            'opened_result' => $openedResult,
                            'clicked_result' => $clickedResult,
                            'opened_explanation' => $decodedContent['opened_explanation'],
                            'clicked_explanation' => $decodedContent['clicked_explanation']
                        ];
                        break;
                    } else {
                        throw new \Exception('Invalid JSON structure');
                    }
                } catch (\Exception $e) {
                    $errorCount++;

                    if ($errorCount >= $maxRetries) {
                        // Log the error or handle it (e.g., save an error status)
                        error_log("Failed to process user_id $userId after $maxRetries retries.");
                    }
                }
            }
        }
        return $results;
    }
}
