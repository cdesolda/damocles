<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HumanFactor;

class HumanFactorController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        HumanFactor::create($validatedData);

        return redirect()->route('phishing-campaign.option')->with('success', 'Added successfully!');
    }

    public function destroy(HumanFactor $humanfactor)
    {
        $humanfactor->delete();

        return redirect()->route('phishing-campaign.option')->with('success', 'Deleted successfully!');
    }
}
