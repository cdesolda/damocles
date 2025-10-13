<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhishingTopic;

class PhishingTopicController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'description' => ['required', 'string', 'max:255'],
        ]);

        PhishingTopic::create($validatedData);

        return redirect()->route('phishing-campaign.option')->with('success', 'Added successfully!');
    }

    public function destroy(PhishingTopic $topic)
    {
        $topic->delete();

        return redirect()->route('phishing-campaign.option')->with('success', 'Deleted successfully!');
    }
}
