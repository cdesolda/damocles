<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Threat;

class ThreatController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Threat::create($validatedData);

        return redirect()->route('phishing-campaign.option')->with('success', 'Added successfully!');
    }

    public function destroy(Threat $threat)
    {
        $threat->delete();

        return redirect()->route('phishing-campaign.option')->with('success', 'Deleted successfully!');
    }
}
