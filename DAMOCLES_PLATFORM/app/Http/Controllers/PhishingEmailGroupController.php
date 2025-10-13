<?php

namespace App\Http\Controllers;

use App\Models\EmailGroup;
use App\Models\PhishingTopic;
use App\Models\PhishingEmail;
use App\Models\PhishingEmailGroup;
use App\Models\PhishingEmotionalTrigger;
use App\Models\PhishingPersuasion;
use Exception;
use Illuminate\Http\Request;

class PhishingEmailGroupController extends Controller
{
    public function index()
    {
        $groups = PhishingEmailGroup::all();
        foreach ($groups as $group) {
            $emailsIdsArray = array_map('intval', explode(',', $group->emails_ids));
            $group->emails = PhishingEmail::whereIn('id', $emailsIdsArray)->get(); 
        }
        return view('phishing-email.email-groups', compact('groups'));
    }

    public function new()
    {
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

        return view('phishing-email.new-email-group', compact(
            'emails',
            'topics',
            'persuasions',
            'emotionalTriggers',
            'groups'
        ));
    }

    public function save(Request $request)
    {
        $request->validate([
            'validatedData' => ['required', 'json'],
            'emailsIds' => ['nullable'], 
        ]);

        $validatedData = json_decode($request->input('validatedData'), true);

        $nameValidation = validator()->make($validatedData, [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($nameValidation->fails()) {
            return back()->withErrors($nameValidation->errors())->withInput();
        }

        $name = $validatedData['name'] ?? null;
        $emailsIds = $request->input('emailsIds', []);
       
        PhishingEmailGroup::create([
            'name' => $name,
            'emails_ids' => $emailsIds,
        ]);

        return redirect()->route('email-groups.index');
    }

    public function destroy(PhishingEmailGroup $group)
    {
        $group->delete();

        return redirect()->back()->with('success', 'Group deleted successfully!');
    }

    public function duplicate($groupId)
    {
        try {
            $group = PhishingEmailGroup::findOrFail($groupId);

            $newGroup = $group->replicate();

            $newGroup->name = $newGroup->name . ' (Copy)';

            $newGroup->save();

            return redirect()->back()->with('success', 'Group duplicated successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function edit($groupId)
    {
        $group = PhishingEmailGroup::findOrFail($groupId);
        $emailsIdsArray = array_map('intval', explode(',', $group->emails_ids));

        $emails = PhishingEmail::all()->map(function ($email) use ($emailsIdsArray) {
            // Decode emotionalTriggers and persuasions IDs
            $emotionalTriggersIds = explode(',', $email->emotional_triggers);
            $persuasionsIds = explode(',', $email->persuasions);

            // Fetch corresponding names
            $email->emotional_triggers = PhishingEmotionalTrigger::whereIn('id', $emotionalTriggersIds)->pluck('description');
            $email->persuasions = PhishingPersuasion::whereIn('id', $persuasionsIds)->pluck('description');
            $email->topic = PhishingTopic::find($email->topic_id)->description ?? 'Unknown Topic';

            // Mark whether the email is already selected for this group
            $email->isSelected = in_array($email->id, $emailsIdsArray);

            return $email;
        });

        $topics = PhishingTopic::all();
        $persuasions = PhishingPersuasion::all();
        $emotionalTriggers = PhishingEmotionalTrigger::all();
        $groups = PhishingEmailGroup::all();

        return view('phishing-email.edit-email-group', compact(
            'group',
            'emails',
            'topics',
            'persuasions',
            'emotionalTriggers',
            'groups'
        ));
    }

    public function updateGroup(Request $request)
    {
        $request->validate([
            'validatedData' => ['required', 'json'],
            'emailsIds' => ['nullable'], 
        ]);

        $validatedData = json_decode($request->input('validatedData'), true);

        $nameValidation = validator()->make($validatedData, [
            'name' => ['required', 'string', 'max:255'],
            'groupId' => ['required'],  // Ensure groupId is present
        ]);

        if ($nameValidation->fails()) {
            return back()->withErrors($nameValidation->errors())->withInput();
        }

        $name = $validatedData['name'] ?? null;
        $groupId = $validatedData['groupId'];
        $emailsIds = $request->input('emailsIds', []);

        // Update emails in the database
        $group = PhishingEmailGroup::find($groupId);
        $group->update([
            'name' => $name,
            'emails_ids' => $emailsIds,
        ]);
  
        return redirect()->route('email-groups.index')->with('success', 'Group updated successfully!');
    }

}
