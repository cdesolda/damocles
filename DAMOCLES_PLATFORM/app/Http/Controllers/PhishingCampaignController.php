<?php

namespace App\Http\Controllers;

use App\Models\PhishingTopic;
use App\Models\PhishingPersuasion;
use App\Models\PhishingEmailPhishingCampaign;
use App\Models\PhishingEmotionalTrigger;
use App\Models\LLM;
use App\Models\PhishingCampaign;
use App\Models\UserPhishingEmail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Jobs\SendPhishingEmails;
use App\Models\Threat;
use App\Models\HumanFactor;
use App\Models\PhishingEmail;
use App\Services\LLMService;
use Carbon\Carbon;
use GuzzleHttp\Client;

class PhishingCampaignController extends Controller
{
    protected $llmService;

    public function __construct(LLMService $llmService)
    {
        $this->llmService = $llmService;
    }

    public function index()
    {
        $user = auth()->user();
        $phishingCampaigns = [];

        if ($user->role === 'Evaluator') {
            $phishingCampaigns = PhishingCampaign::where('evaluator_id', $user->id)->get();
        }

        return view('phishing-campaign.phishing-campaign', compact('phishingCampaigns'));
    }

    public function new()
    {
        return view('phishing-campaign.new-phishing-campaign');
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'expirationDate' => ['required'],
            'evaluatorId' => ['required', 'integer'],
            'state' => ['required', 'string', 'max:255'],
        ]);

        $validatedData['expiration_date'] = $validatedData['expirationDate'];
        $validatedData['evaluator_id'] = $validatedData['evaluatorId'];

        $phishingCampaign = PhishingCampaign::create($validatedData);

        return redirect()->route('phishing-campaign.add-details', ['phishingCampaign' => $phishingCampaign->id]);
    }

    public function addDetails($phishingCampaignId)
    {
        $phishingCampaign = PhishingCampaign::findOrFail($phishingCampaignId);

        $topics = PhishingTopic::all();
        $topicsGroup = $topics->groupBy('description');
        $topics = $topics->unique('description');
        $persuasions = PhishingPersuasion::all();
        $emotionalTriggers = PhishingEmotionalTrigger::all();
        $llms = LLM::all();

        return view('phishing-campaign.new-details-phishing-campaign', compact('phishingCampaign', 'topics', 'topicsGroup', 'persuasions', 'emotionalTriggers', 'llms'));
    }

    public function extractTopic(Request $request)
    {
        try {
            $validated = $request->validate([
                'emailTextArea' => ['required'],
            ]);

            $prompt = 'Given this email "' . $validated['emailTextArea'] . '" extract the main topic (the context) of the email and regenerate a better email. Generate the response strictly in the following JSON format without any extra text:
                {
                  "topic": "topic of the email",
                  "subject": "subject of the email",
                  "body": "body of the email"
                }';

            $llm = LLM::first();

            if (!$llm) {
                throw new \Exception('No LLM instance found.');
            }

            // Extract the topic
            //$generatedData = ['topic' => 'Topic goes here!', 'topic' => 'Subject goes here!', 'email' => 'Body email goes here!'];
            $tempContent = $this->llmService->generateChatGPTHTTPPost($llm, $prompt);
            $cleanContent = $this->llmService->extractJson($tempContent);
            $generatedData = json_decode($cleanContent, true);

            $newTopic = ['description' => $generatedData['topic'], 'subject_email' => $generatedData['subject'], 'body_email' => $generatedData['body']];
            $newTopic = PhishingTopic::create($newTopic);

            $generatedData['topic'] = [
                'id' => $newTopic->id,
                'description' => $newTopic->description
            ];

            return response()->json([
                'success' => true,
                'emailData' => $generatedData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the request.'
            ], 500);
        }
    }

    public function addDetailsEmailExample(Request $request)
    {
        try {
            $validated = $request->validate([
                'subject' => ['required'],
                'body' => ['required'],
                'type' => ['required'],
                'values' => ['required'],
            ]);

            $llm = LLM::first();

            if (!$llm) {
                throw new \Exception('No LLM instance found.');
            }

            $values = implode(', ', $validated['values']);

            $prompt = 'Given this email "' . $validated['body'] . '" with this subject  "' . $validated['subject'] . '" ';

            if ($validated['type'] == 'persuasions') {
                //$generatedData = ['subject' => 'New Subject with persuasions goes here!', 'body' => 'New body email with persuasions goes here!'];
                $prompt .=  'add details about persuasions and are: ' . $values . '. Generate the response strictly in the following JSON format without any extra text:
                    {
                      "subject": "subject of the email",
                      "body": "body of the email"
                    }';
            }
            if ($validated['type'] == 'emotional-triggers') {
                //$generatedData = ['subject' => 'New Subject with emotional trigger goes here!', 'body' => 'New body email with emotional trigger goes here!'];
                $prompt .=  ' and emotional triggers are: ' . $values . '. Generate the response strictly in the following JSON format without any extra text:
                    {
                      "subject": "subject of the email",
                      "body": "body of the email"
                    }';
            }

            $tempContent = $this->llmService->generateChatGPTHTTPPost($llm, $prompt);
            $cleanContent = $this->llmService->extractJson($tempContent);
            $generatedData = json_decode($cleanContent, true);

            return response()->json([
                'success' => true,
                'emailData' => $generatedData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the request.'
            ], 500);
        }
    }

    public function saveDetails(Request $request)
    {
        $validatedData = $request->validate([
            'id' => ['required', 'integer'],
            'numberEmails' => ['required', 'integer', 'min:1', 'max:10'],
            'topicId' => ['required', 'integer'],
            'persuasions' => ['nullable'],
            'emotionalTriggers' => ['nullable'],
            'llmId' => ['required', 'integer'],
            'emailSubjectExample' => ['required'],
            'emailBodyExample' => ['required'],
            'timingEmail' => ['required', 'integer'],
        ]);

        $validatedData['topic_id'] = $validatedData['topicId'];
        $validatedData['llm_id'] = $validatedData['llmId'];

        $phishingCampaign = PhishingCampaign::findOrFail($validatedData['id']);
        $topic = PhishingTopic::findOrFail($validatedData['topicId']);

        $prompt = 'Please provide an example of a phishing email with a subject ' . $phishingCampaign->title . ' and body ' . $phishingCampaign->description .
            ' that focuses on ' . $topic->description . ', starting from this email: 
                {
                    "subject": "' . $validatedData['emailSubjectExample'] . '",
                    "body": "' . $validatedData['emailBodyExample'] . '",
                }';


        if (is_string($validatedData['persuasions'])) {
            $persuasionsIds = explode(',', $validatedData['persuasions']);
        } else {
            $persuasionsIds = [];
        }

        if (is_string($validatedData['emotionalTriggers'])) {
            $emotionalTriggersIds = explode(',', $validatedData['emotionalTriggers']);
        } else {
            $emotionalTriggersIds = [];
        }

        $persuasions = PhishingPersuasion::whereIn('id', $persuasionsIds)->get();
        $emotionalTriggers = PhishingEmotionalTrigger::whereIn('id', $emotionalTriggersIds)->get();

        if ($persuasions->count() > 0) {
            $descriptions = $persuasions->pluck('description')->implode(', ');

            if ($persuasions->count() == 1) {
                $prompt .= ", utilizes the Cialdini's persuasion principle: " . $descriptions;
            } else {
                $prompt .= ", utilizes the Cialdini's persuasion principles: " . $descriptions;
            }
        }

        if ($emotionalTriggers->count() > 0) {
            $descriptions = $emotionalTriggers->pluck('description')->implode(', ');

            if ($emotionalTriggers->count() == 1) {
                $prompt .= ", and emotional trigger: " . $descriptions;
            } else {
                $prompt .= ", and emotional trigger: " . $descriptions;
            }
        }
        $prompt = $prompt . ".";

        $phishingCampaign->number_emails = $validatedData['numberEmails'];
        $phishingCampaign->topic_id = $validatedData['topic_id'];
        $phishingCampaign->emotional_triggers = $validatedData['emotionalTriggers'];
        $phishingCampaign->persuasions = $validatedData['persuasions'];
        $phishingCampaign->llm_id = $validatedData['llm_id'];
        $phishingCampaign->prompt = $prompt;
        $phishingCampaign->timing_email = $validatedData['timingEmail'];

        $phishingCampaign->save();

        return redirect()->route('phishing-campaign.generate-emails', ['phishingCampaign' => $phishingCampaign->id]);
    }

    public function generate($phishingCampaignId)
    {
        $phishingCampaign = PhishingCampaign::findOrFail($phishingCampaignId);

        $emotionalTriggersIds = explode(',', $phishingCampaign->emotional_triggers);
        $persuasionsIds = explode(',', $phishingCampaign->persuasions);

        $persuasions = PhishingPersuasion::whereIn('id', $persuasionsIds)->get();
        $emotionalTriggers = PhishingEmotionalTrigger::whereIn('id', $emotionalTriggersIds)->get();

        $llm = LLM::findOrFail($phishingCampaign->llm_id);
        $numberEmails = $phishingCampaign->number_emails;
        $prompt = $phishingCampaign->prompt . ' You must use fake organization nationality real names for the sender. Generate the email strictly in the following JSON format without any extra text:

            {
              "subject": "subject of the email",
              "body": "body of the email",
              "explanation": "eplanation why this phishing email is effective and ideal targets of this scam (into markdown style)"
            }
            
            The email body must include these placeholders exactly as written: -name, -surname, -email, -dob, -clickHere.';

        $existingEmails = PhishingEmailPhishingCampaign::where('phishing_campaign_id', $phishingCampaign->id);
        $existingEmails->delete();

        // Generate new emails (1 or 2 emails)
        //$generatedEmails = ['subjects' => ['Subject goes here!'], 'bodies' => ['Body goes here!'], 'explanations' => ['Explanation goes here!']];
        //$generatedEmails = ['subjects' => ['Subject goes here!', 'Subject goes here!'], 'bodies' => ['Body goes here!', 'Body goes here!'], 'explanations' => ['Explanation goes here!', 'Explanation goes here!']];
        $generatedEmails = $this->llmService->generateEmails($numberEmails, $llm, $prompt);

        // Save the generated emails to the database
        $this->savePhishingEmail($generatedEmails, $phishingCampaign);

        return view('phishing-campaign.generated-email', [
            'phishingCampaign' => $phishingCampaign,
            'persuasions' => $persuasions,
            'emotionalTriggers' => $emotionalTriggers,
            'llm' => $llm,
            'subjects' => $generatedEmails['subjects'],
            'bodies' => $generatedEmails['bodies'],
            'explanations' => $generatedEmails['explanations'],
            'state' => $phishingCampaign->state,
        ]);
    }

    public function rewriteEmail(Request $request)
    {
        try {
            $validated = $request->validate([
                'llmId' => ['required', 'integer'],
                'subject' => ['required'],
                'body' => ['required'],
                'explanation' => ['required'],
                'selected_text' => ['required'],
                'rewriteTextArea' => ['required'],
            ]);

            $llm = LLM::findOrFail($validated['llmId']);

            $prompt = 'Given this email "' . $validated['body'] . '". You must edit this selected text "' . $validated['selected_text'] . '" with this request "' . $validated['rewriteTextArea'] . '". Generate the email strictly in the following JSON format without any extra text:

                {
                  "subject": "' . $validated['subject'] . '",
                  "body": "body of the email edited",
                  "explanation": "eplanation why this phishing email is effective and ideal targets of this scam (into markdown style)"
                }';

            // Generate new email
            //$generatedEmail = ['subjects' => ['Subject goes here!'], 'bodies' => ['Body goes here!'], 'explanations' => ['Explanation goes here!']];
            $generatedEmail = $this->llmService->generateEmails(1, $llm, $prompt);

            $updatedData = [
                'subject' => $generatedEmail['subjects'],
                'body' => $generatedEmail['bodies'],
                'explanation' => $generatedEmail['explanations'],
            ];

            return response()->json([
                'success' => true,
                'updatedData' => $updatedData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the request.'
            ], 500);
        }
    }

    public function savePhishingEmails(Request $request)
    {
        $validatedData = $request->validate([
            'phishingCampaignId' => ['required', 'integer'],
            'subjects' => ['required', 'array'],
            'subjects.*' => ['required', 'string', 'max:255'],
            'bodies' => ['required', 'array'],
            'bodies.*' => ['required'],
            'explanations' => ['required', 'array'],
            'explanations.*' => ['required'],
        ]);

        $phishingCampaignId = $validatedData['phishingCampaignId'];

        $phishingCampaign = PhishingCampaign::findOrFail($phishingCampaignId);

        PhishingEmailPhishingCampaign::where('phishing_campaign_id', $phishingCampaignId)->delete();

        $generatedEmails = [
            'subjects' => $validatedData['subjects'],
            'bodies' => $validatedData['bodies'],
            'explanations' => $validatedData['explanations'],
        ];

        $this->savePhishingEmail($generatedEmails, $phishingCampaign);

        return redirect()->route('phishing-campaign.users', ['phishingCampaign' => $phishingCampaignId]);
    }

    public function users($phishingCampaignId)
    {
        $phishingCampaign = PhishingCampaign::findOrFail($phishingCampaignId);
        $phishingEmailPhishingCampaign = PhishingEmailPhishingCampaign::where('phishing_campaign_id', $phishingCampaignId)->firstOrFail();

        $users = User::where('role', 'User')->where('type', 'Real')->where('is_active', true)->where('is_accept', true)->get();

        return view('phishing-campaign.users-phishing', compact('users', 'phishingCampaignId'));
    }

    public function saveUsersPhishingEmails(Request $request)
    {
        $validatedData = $request->validate([
            'phishingCampaignId' => ['required', 'integer'],
            'usersIds' => ['required'],
            'usersIds.*' => ['required'],
        ]);

        $phishingCampaignId = $validatedData['phishingCampaignId'];
        $usersIds = explode(',', $validatedData['usersIds']);

        $phishingEmailPhishingCampaigns = PhishingEmailPhishingCampaign::where('phishing_campaign_id', $phishingCampaignId)->get();
        $emailIds = $phishingEmailPhishingCampaigns->pluck('email_id');

        if (empty($usersIds) || empty($emailIds)) {
            return response()->json(['error' => 'Users or Phishing Campaign Id cannot be null'], 400);
        }

        foreach ($usersIds as $userId) {
            foreach ($phishingEmailPhishingCampaigns as $phishingEmailPhishingCampaign) {
                UserPhishingEmail::create([
                    'user_id' => $userId,
                    'pe_pc_id' => $phishingEmailPhishingCampaign->id,
                ]);
            }
        }

        $this->changeState($phishingCampaignId, 'Ready');
        return redirect()->route('phishing-campaign.index')->with('success', 'Emails assigned successfully.');
    }

    public function changeState($phishingCampaignId, $state)
    {
        $phishingCampaign = PhishingCampaign::findOrFail($phishingCampaignId);
        $phishingCampaign->state = $state;
        $phishingCampaign->save();

        if ($state != 'Draft' && $state != 'Ready') {

            if ($state == 'Live') {
                $this->sendEmails($phishingCampaign);
                return redirect()->back()->with('success', 'Campaign start successfully!');
            }

            if ($state == 'Completed') {
                return redirect()->back()->with('success', 'Campaign stopped successfully!');
            }

            return redirect()->back();
        }
    }

    public function sendEmails(PhishingCampaign $phishingCampaign)
    {
        // Collect the phishingEmailPhishingCampaigns IDs associated with the campaign
        $phishingEmailPhishingCampaigns = $phishingCampaign->phishingEmailPhishingCampaign;
        $phishingEmailPhishingCampaignIds = $phishingEmailPhishingCampaigns->pluck('id')->toArray();

        // Retrieve all email not sent
        $emailsNotSent = UserPhishingEmail::whereIn('pe_pc_id', $phishingEmailPhishingCampaignIds)
            ->whereNull('sent')
            ->get();

        // Get the first email not sent ID
        $emailId = $emailsNotSent->first();

        // Retrieve the details of the first email not sent
        $email = PhishingEmailPhishingCampaign::find($emailId->pe_pc_id)->email;

        // Retrieve the user IDs associated with that email
        $userPhishingEmails = UserPhishingEmail::where('pe_pc_id', $emailId->pe_pc_id)->get();

        if ($phishingCampaign->state == "Live") {
            DB::beginTransaction();
            try {
                foreach ($userPhishingEmails as $userPhishingEmail) {
                    try {
                        $subject = $this->replacePlaceholders($userPhishingEmail->id, $email->subject, $userPhishingEmail->user);
                        $body = $this->replacePlaceholders($userPhishingEmail->id, $email->body, $userPhishingEmail->user);

                        // Dispatch the job
                        SendPhishingEmails::dispatch($userPhishingEmail->id, $userPhishingEmail->user, $subject, $body);

                        if ($userPhishingEmail) {
                            $userPhishingEmail->sent = Carbon::now();
                            $userPhishingEmail->save();
                        }
                    } catch (\Exception $e) {
                        // Handle exception if necessary
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'Failed to dispatch jobs: ' . $e->getMessage()], 500);
            }
        }
    }

    public function opened($userPhishingEmailId)
    {
        $userPhishingEmail = UserPhishingEmail::findOrFail($userPhishingEmailId);
        $phishingCampaign = $userPhishingEmail->phishingEmailPhishingCampaign->phishingCampaign;

        if ($phishingCampaign->state === 'Live' && $userPhishingEmail->sent != null) {
            $userPhishingEmail->opened = Carbon::now();
            $userPhishingEmail->save();
        }

        return response()->noContent();
    }

    public function clicked($userPhishingEmailId)
    {
        $userPhishingEmail = UserPhishingEmail::findOrFail($userPhishingEmailId);
        $phishingCampaign = $userPhishingEmail->phishingEmailPhishingCampaign->phishingCampaign;

        if ($phishingCampaign->state === 'Live' && $userPhishingEmail->sent != null && $userPhishingEmail->opened != null) {
            $userPhishingEmail->clicked = Carbon::now();
            $userPhishingEmail->save();
        }

        return response()->noContent();
    }

    public function analyse($phishingCampaignId)
    {
        $phishingCampaign = PhishingCampaign::findOrFail($phishingCampaignId);

        // Collect the email IDs associated with the campaign
        $phishingEmailPhishingCampaigns = $phishingCampaign->phishingEmailPhishingCampaign;
        $phishingEmailPhishingCampaignIds = $phishingEmailPhishingCampaigns->pluck('id')->toArray();

        // Collect the users and their click status for the emails in this campaign
        $userPhishingEmails = UserPhishingEmail::whereIn('pe_pc_id', $phishingEmailPhishingCampaignIds)->get();

        // Email statistics
        $emailSent = $userPhishingEmails->whereNotNull('sent');
        $emailOpened = $emailSent->whereNotNull('opened');
        $emailNotOpened = $emailSent->whereNull('opened');
        $emailClicked = $emailSent->whereNotNull('clicked');
        $emailNotClicked = $emailSent->whereNull('clicked');

        // Count male, female, and other users who opened the phishing email
        $openedMaleCount = $emailOpened->filter(fn($email) => $email->user->gender === 'Male')->count();
        $openedFemaleCount = $emailOpened->filter(fn($email) => $email->user->gender === 'Female')->count();
        $openedOtherCount = $emailOpened->filter(fn($email) => $email->user->gender === 'Other')->count();

        // Count male, female, and other users who clicked the phishing link
        $clickedMaleCount = $emailClicked->filter(fn($email) => $email->user->gender === 'Male')->count();
        $clickedFemaleCount = $emailClicked->filter(fn($email) => $email->user->gender === 'Female')->count();
        $clickedOtherCount = $emailClicked->filter(fn($email) => $email->user->gender === 'Other')->count();

        $userEmailData = $userPhishingEmails
            ->groupBy('user_id') // Group by user ID
            ->map(function ($emails) {
                // Extract user info from the first email (all emails share the same user)
                $user = $emails->first()->user;

                // Map emails to include relevant fields
                $emailDetails = $emails->map(function ($email) {
                    $pe_pc_Id = PhishingEmailPhishingCampaign::where('id', $email->pe_pc_id)->first();

                    return [
                        'email' => $pe_pc_Id && $pe_pc_Id->email ? $pe_pc_Id->email->subject : null, // Accedi al subject se esiste
                        'sent' => $email->sent,
                        'opened' => $email->opened,
                        'clicked' => $email->clicked,
                        'updated_at' => $email->updated_at,
                    ];
                });

                return [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'surname' => $user->surname,
                        'gender' => $user->gender,
                        'email' => $user->email,
                    ],
                    'emails' => $emailDetails->toArray(),
                ];
            })
            ->values() // Reset keys for the outer collection
            ->toArray();

        return view('phishing-campaign.phishing-campaign-analyse', compact('phishingCampaign', 'userEmailData', 'userPhishingEmails', 'emailSent', 'emailOpened', 'emailNotOpened', 'emailClicked', 'emailNotClicked', 'openedMaleCount', 'openedFemaleCount', 'openedOtherCount', 'clickedMaleCount', 'clickedFemaleCount', 'clickedOtherCount'));
    }

    public function stop($phishingCampaignId)
    {
        try {
            $phishingCampaign = PhishingCampaign::findOrFail($phishingCampaignId);
            $this->changeState($phishingCampaignId, 'Completed');
            return redirect()->back()->with('success', 'Campaign stopped successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function details($phishingCampaignId)
    {
        $phishingCampaign = PhishingCampaign::findOrFail($phishingCampaignId);

        $emotionalTriggersIds = explode(',', $phishingCampaign->emotional_triggers);
        $persuasionsIds = explode(',', $phishingCampaign->persuasions);

        $persuasions = PhishingPersuasion::whereIn('id', $emotionalTriggersIds)->get();
        $emotionalTriggers = PhishingEmotionalTrigger::whereIn('id', $persuasionsIds)->get();

        $phishingEmailPhishingCampaigns = $phishingCampaign->phishingEmailPhishingCampaign;
        $phishingEmailPhishingCampaignIds = $phishingEmailPhishingCampaigns->pluck('id')->toArray();

        // Collect the email with the foreign key
        $uemailsIds = $phishingEmailPhishingCampaigns->pluck('email_id')->toArray();
        $emails = PhishingEmail::whereIn('id', $uemailsIds)->get();

        // Collect the users with the foreign key from UserPhishingEmail
        $usersIds = UserPhishingEmail::whereIn('pe_pc_id', $phishingEmailPhishingCampaignIds)->distinct()->pluck('user_id')->toArray();

        $users = User::whereIn('id', $usersIds)->get();

        return view('phishing-campaign.phishing-campaign-detail', compact('phishingCampaign', 'persuasions', 'emotionalTriggers', 'emails', 'users'));
    }

    public function downloadDataCSV($phishingCampaignId)
    {
        try {
            $phishingCampaign = PhishingCampaign::findOrFail($phishingCampaignId);

            $csvData = $this->generateDataCSV([$phishingCampaign]);

            return $this->createCSVResponse($csvData, 'phishing_campaign_' . $phishingCampaignId);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function downloadAllDataCSV()
    {
        try {
            $phishingCampaigns = PhishingCampaign::all();

            $csvData = $this->generateDataCSV($phishingCampaigns);

            return $this->createCSVResponse($csvData, 'phishing_campaigns');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function duplicate($phishingCampaignId)
    {
        try {
            $phishingCampaign = PhishingCampaign::findOrFail($phishingCampaignId);

            $newPhishingCampaign = $phishingCampaign->replicate();

            $newPhishingCampaign->state = 'Draft';
            $newPhishingCampaign->title .= ' (Copy)';

            $newPhishingCampaign->save();

            return redirect()->back()->with('success', 'Campaign duplicated successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function destroy(PhishingCampaign $phishingCampaign)
    {
        $phishingCampaign->delete();

        return redirect()->back()->with('success', 'Campaign deleted successfully!');
    }

    public function option()
    {
        $topics =  PhishingTopic::all();
        $emotionalTriggers = PhishingEmotionalTrigger::all();
        $persuasions =  PhishingPersuasion::all();
        $threats =  Threat::all();
        $humanfactors = HumanFactor::all();

        return view('phishing-campaign.phishing-campaign-option', compact('topics', 'emotionalTriggers', 'persuasions', 'threats', 'humanfactors'));
    }

    public function savePhishingEmail($generatedEmails, $phishingCampaign)
    {
        foreach ($generatedEmails['subjects'] as $index => $subject) {
            $email = PhishingEmail::create([
                'subject' => $generatedEmails['subjects'][$index],
                'body' => $generatedEmails['bodies'][$index],
                'explanation' => $generatedEmails['explanations'][$index],
                'topic_id' => $phishingCampaign->topic_id,
                'emotional_triggers' => $phishingCampaign->emotional_triggers,
                'persuasions' => $phishingCampaign->persuasions,
                'llm_id' => $phishingCampaign->llm_id,
                'prompt' => $phishingCampaign->prompt,
            ]);

            PhishingEmailPhishingCampaign::create([
                'phishing_campaign_id' => $phishingCampaign->id,
                'email_id' => $email->id,
            ]);
        }
    }

    private function replacePlaceholders($userPhishingEmailId, $text, $user)
    {
        $url = config('app.url') . "/clicked/" . $userPhishingEmailId;

        $shortLink = $this->shortenUrl($url);

        $placeholders = [
            '-name' => $user->name,
            '-surname' => $user->surname,
            '-email' => $user->email,
            '-dob' => $user->dob,
            '-clickHere' => $shortLink !== null ? $shortLink : '',
        ];

        foreach ($placeholders as $placeholder => $value) {
            $text = str_replace($placeholder, $value, $text);
        }

        return $text;
    }

    private function shortenUrl($longUrl)
    {
        // Create a new instance of Guzzle client
        $client = new Client();

        // URL of the TinyURL API
        $url = 'http://tinyurl.com/api-create.php?url=' . urlencode($longUrl);

        try {
            // Make a GET request to the TinyURL API
            $response = $client->get($url);

            // Get the shortened URL from the response body
            $shortUrl = (string) $response->getBody();

            return $shortUrl;
        } catch (\Exception $e) {
            // Handle any errors
            return null;
        }
    }

    private function generateDataCSV($phishingCampaigns)
    {
        $csvData = [
            ['ID', 'Phishing Campaign ID', 'Title', 'Description', 'State', 'Number of Emails', 'Topic', 'Emotional Triggers', 'Persuasions', 'Subject', 'Body', 'User name', 'User surname', 'User gender', 'User dob', 'User company role', 'Sent', 'Opened', 'Clicked']
        ];

        $incrementalId = 1;

        foreach ($phishingCampaigns as $phishingCampaign) {
            $emotionalTriggersIds = explode(',', $phishingCampaign->emotional_triggers);
            $persuasionsIds = explode(',', $phishingCampaign->persuasions);

            $persuasions = PhishingPersuasion::whereIn('id', $persuasionsIds)->pluck('description')->toArray();
            $emotionalTriggers = PhishingEmotionalTrigger::whereIn('id', $emotionalTriggersIds)->pluck('description')->toArray();
            $topic = PhishingTopic::where('id', $phishingCampaign->topic_id)->pluck('description')->first();

            $topic = $topic ?: null;
            $persuasionsDescriptions = "'" . implode("', '", $persuasions) . "'";
            $emotionalTriggersDescriptions = "'" . implode("', '", $emotionalTriggers) . "'";

            $phishingEmailsPhishingCampaign = PhishingEmailPhishingCampaign::where('phishing_campaign_id', $phishingCampaign->id)->get();
            $phishingEmailsPhishingCampaignIds = $phishingEmailsPhishingCampaign->pluck('id')->toArray();

            $emailsIds = $phishingEmailsPhishingCampaign->pluck('email_id')->toArray();
            $emails = PhishingEmail::whereIn('id', $emailsIds)->get();

            $usersPhishingEmails = UserPhishingEmail::where('pe_pc_id', $phishingEmailsPhishingCampaignIds)->get();
            $usersPhishingEmailsIds = $usersPhishingEmails->pluck('id')->toArray();

            if ($emails->isEmpty()) {
                $csvData[] = [
                    $incrementalId++,
                    $phishingCampaign->id,
                    $phishingCampaign->title,
                    $phishingCampaign->description,
                    $phishingCampaign->state,
                    $phishingCampaign->number_emails,
                    $topic,
                    $emotionalTriggersDescriptions,
                    $persuasionsDescriptions,
                    '', // Empty Subject
                    '', // Empty Body
                    '', // Empty User name
                    '', // Empty User surname
                    '', // Empty User gender
                    '', // Empty User dob
                    '', // Empty User company role
                    '', // Empty Sent
                    '', // Empty Opened
                    '', // Empty Clicked
                ];
            } else {
                foreach ($emails as $email) {

                    $userPhishingEmails = UserPhishingEmail::where('id', $usersPhishingEmailsIds)->get();

                    if ($userPhishingEmails->isEmpty()) {
                        $csvData[] = [
                            $incrementalId++,
                            $phishingCampaign->id,
                            $phishingCampaign->title,
                            $phishingCampaign->description,
                            $phishingCampaign->state,
                            $phishingCampaign->number_emails,
                            $topic,
                            $emotionalTriggersDescriptions,
                            $persuasionsDescriptions,
                            $email->subject,
                            $email->body,
                            '', // Empty User name
                            '', // Empty User surname
                            '', // Empty User gender
                            '', // Empty User dob
                            '', // Empty User company role
                            '', // Empty Sent
                            '', // Empty Opened
                            '', // Empty Clicked
                        ];
                    } else {
                        foreach ($userPhishingEmails as $userPhishingEmail) {

                            $user = User::find($userPhishingEmail->user_id);

                            $emailSubject = $email->subject ?: null;
                            $emailBody = $email->body ?: null;

                            $userName = $user->name ?? null;
                            $userSurname = $user->surname ?? null;
                            $userGender = $user->gender ?? null;
                            $userDob = $user->dob ?? null;
                            $userCompanyRole = $user->company_role ?? null;

                            $sent = $userPhishingEmail->sent ?: null;
                            $opened = $userPhishingEmail->opened ?: null;
                            $clicked = $userPhishingEmail->clicked ?: null;

                            $csvData[] = [
                                $incrementalId++,
                                $phishingCampaign->id,
                                $phishingCampaign->title,
                                $phishingCampaign->description,
                                $phishingCampaign->state,
                                $phishingCampaign->number_emails,
                                $topic,
                                $emotionalTriggersDescriptions,
                                $persuasionsDescriptions,
                                $emailSubject,
                                $emailBody,
                                $userName,
                                $userSurname,
                                $userGender,
                                $userDob,
                                $userCompanyRole,
                                $sent,
                                $opened,
                                $clicked,
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
}
