<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingPrompt;

class TrainingPromptController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'pros' => ['required', 'string'],
            'cons' => ['required', 'string'],
        ]);

        TrainingPrompt::create($validatedData);

        return redirect()->route('training-campaign.option')->with('success', 'Added successfully!');
    }

    public function destroy(TrainingPrompt $prompt)
    {
        $prompt->delete();

        return redirect()->route('training-campaign.option')->with('success', 'Deleted successfully!');
    }
}
