<?php

namespace App\Http\Controllers;

use App\Models\DigitalTwinsResult;
use App\Models\PhishingEmailPhishingCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\PhishingEmail;
use App\Models\PhishingTopic;
use App\Models\PhishingPersuasion;
use App\Models\PhishingEmotionalTrigger;
use App\Models\LLM;
use App\Services\LLMService;
use Exception;

class PhishingEmailController extends Controller
{
    protected $llmService;

    public function __construct(LLMService $llmService)
    {
        $this->llmService = $llmService;
    }

    public function index()
    {
        $emails = PhishingEmail::all();
        return view('phishing-email.email', compact('emails'));
    }

    public function new()
    {
        $topics = PhishingTopic::all();
        $topicsGroup = $topics->groupBy('description');
        $topics = $topics->unique('description');
        $persuasions = PhishingPersuasion::all();
        $emotionalTriggers = PhishingEmotionalTrigger::all();
        $llms = LLM::all();

        return view('phishing-email.new-email', compact('topics', 'topicsGroup', 'persuasions', 'emotionalTriggers', 'llms'));
    }

    public function generate(Request $request)
    {
        $validatedData = $request->validate([
            'mainTopic' => ['required', 'string', 'max:255'],
            'topicDetails' => ['required', 'string'],
            'numberEmails' => ['required', 'integer', 'min:1', 'max:10'],
            'topicId' => ['required', 'integer'],
            'emotionalTriggers' => ['nullable'],
            'persuasions' => ['nullable'],
            'llmId' => ['required', 'integer'],
            'emailSubjectExample' => ['required'],
            'emailBodyExample' => ['required'],
        ]);

        $validatedData['topic_id'] = $validatedData['topicId'];
        $validatedData['llm_id'] = $validatedData['llmId'];

        $topic = PhishingTopic::findOrFail($validatedData['topic_id']);
        $llm = LLM::findOrFail($validatedData['llm_id']);

        $prompt = 'Please provide an example of a phishing email with a subject ' . $validatedData['mainTopic'] . ' and body ' . $validatedData['topicDetails'] .
            ' that focuses on ' . $topic->description . ', starting from this email: 
            {
                "subject": "' . $validatedData['emailSubjectExample'] . '",
                "body": "' . $validatedData['emailBodyExample'] . '",
            }';

        if (is_string($validatedData['emotionalTriggers'])) {
            $emotionalTriggersIds = explode(',', $validatedData['emotionalTriggers']);
        } else {
            $emotionalTriggersIds = [];
        }

        if (is_string($validatedData['persuasions'])) {
            $persuasionsIds = explode(',', $validatedData['persuasions']);
        } else {
            $persuasionsIds = [];
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

        $prompt = $prompt . ' You must use fake organization nationality real names for the sender. Generate the email strictly in the following JSON format without any extra text:

            {
              "subject": "subject of the email",
              "body": "body of the email",
              "explanation": "eplanation why this phishing email is effective and ideal targets of this scam (into markdown style)"
            }
            
            The email body must include these placeholders exactly as written: -name, -surname, -email, -dob, -clickHere.';

        // Generate new emails
        //$generatedEmails = ['subjects' => ['Subject goes here!'], 'bodies' => ['Body goes here!'], 'explanations' => ['Explanation goes here!']];
        $generatedEmails = $this->llmService->generateEmails($validatedData['numberEmails'], $llm, $prompt);

        // Save the generated emails to the database
        $emailIds = $this->savePhishingEmails($generatedEmails, $topic->id, $validatedData['emotionalTriggers'], $validatedData['persuasions'], $llm->id, $prompt);

        return view('phishing-email.generated-email', [
            'numberEmails' => $validatedData['numberEmails'],
            'topic' => $topic,
            'persuasions' => $persuasions,
            'emotionalTriggers' => $emotionalTriggers,
            'llm' => $llm,
            'subjects' => $generatedEmails['subjects'],
            'bodies' => $generatedEmails['bodies'],
            'explanations' => $generatedEmails['explanations'],
            'emailIds' => $emailIds,
        ]);
    }

    public function edit($emailId)
    {
        $email = PhishingEmail::findOrFail($emailId);

        $emotionalTriggersIds = explode(',', $email->emotionalTriggers);
        $persuasionsIds = explode(',', $email->persuasions);

        $persuasions = PhishingPersuasion::whereIn('id', $persuasionsIds)->get();
        $emotionalTriggers = PhishingEmotionalTrigger::whereIn('id', $emotionalTriggersIds)->get();

        return view('phishing-email.edit-email', [
            'topic' => $email->phishingTopic,
            'persuasions' => $persuasions,
            'emotionalTriggers' => $emotionalTriggers,
            'llm' => $email->llm,
            'subjects' => [$email->subject],
            'bodies' => [$email->body],
            'explanations' => [$email->explanation],
            'emailIds' => [$email->id],
        ]);
    }

    public function duplicate($emailId)
    {
        try {
            $email = PhishingEmail::findOrFail($emailId);

            $newEmail = $email->replicate();

            $newEmail->subject = $newEmail->subject . ' (Copy)';

            $newEmail->save();

            return redirect()->back()->with('success', 'Email duplicated successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    private function savePhishingEmails($generatedEmails, $topicId, $emotionalTriggers, $persuasions, $llmId, $prompt)
    {
        $emailIds = [];
        foreach ($generatedEmails['subjects'] as $index => $subject) {
            $email = PhishingEmail::create([
                'subject' => $generatedEmails['subjects'][$index],
                'body' => $generatedEmails['bodies'][$index],
                'explanation' => $generatedEmails['explanations'][$index],
                'topic_id' => $topicId,
                'emotional_triggers' => $emotionalTriggers,
                'persuasions' => $persuasions,
                'llm_id' => $llmId,
                'prompt' => $prompt,
            ]);
            $emailIds[] = $email->id;
        }
        return $emailIds;
    }

    public function updatePhishingEmails(Request $request)
    {
        $request->validate([
            'email_ids' => 'required|array',
            'email_ids.*' => 'exists:phishing_emails,id',
            'subjects' => 'required|array',
            'subjects.*' => 'string|max:255',
            'bodies' => 'required|array',
            'bodies.*' => 'string',
            'explanations' => 'required|array',
            'explanations.*' => 'string',
        ]);

        // Retrieve the data
        $emailIds = $request->input('email_ids');
        $subjects = $request->input('subjects');
        $bodies = $request->input('bodies');
        $explanations = $request->input('explanations');

        // Update emails in the database
        foreach ($emailIds as $index => $emailId) {
            PhishingEmail::where('id', $emailId)->update([
                'subject' => $subjects[$index] ?? null,
                'body' => $bodies[$index] ?? null,
                'explanation' => $explanations[$index] ?? null,
            ]);
        }

        return redirect()->route('emails.index')->with('success', 'Emails updated successfully!');
    }

    public function destroy(PhishingEmail $email)
    {
        $email->delete();

        return redirect()->back()->with('success', 'Email deleted successfully!');
    }

    public function multipleDestroy($emails)
    {
        $ids = explode(',', $emails);
        $emails = PhishingEmail::whereIn('id', $ids)->get();

        $undeletedEmails = [];
        $deletedCount = 0;

        foreach ($emails as $email) {
            // Check if email is referenced in Digital Twins Results or Phishing Emails Phishing Campaigns
            $isReferenced = DigitalTwinsResult::where('email_id', $email->id)->exists() ||
                PhishingEmailPhishingCampaign::where('email_id', $email->id)->exists();

            if ($isReferenced) {
                $undeletedEmails[] = $email->subject;
            } else {
                $email->delete();
                $deletedCount++;
            }
        }

        if ($deletedCount > 0 && count($undeletedEmails) > 0) {
            return redirect()->back()->with('delete-warning', $undeletedEmails);
        } elseif ($deletedCount > 0) {
            return redirect()->back()->with('delete-success', __('email.deleteMessages.explanation.success'));
        } else {
            return redirect()->back()->with('delete-error', __('email.deleteMessages.explanation.notDeleted'));
        }
    }
}
