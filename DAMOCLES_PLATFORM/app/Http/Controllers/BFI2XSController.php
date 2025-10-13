<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BFI2XS; 
use App\Models\Questionnaire;
use App\Models\UserQuestionnaireAnswer;
use App\Models\QuestionnaireCampaign;
use App\Models\UserHfThreat;
use App\Models\HumanFactor;
use App\Models\Threat;

class BFI2XSController extends Controller
{
    public function index($questionnaireCampaignId, $questionnaireId)
    {
        $questionnaireCampaign = QuestionnaireCampaign::findOrFail($questionnaireCampaignId);
        $questionnaire = Questionnaire::findOrFail($questionnaireId);

        return view('questionnaires-campaign.bfi2xs.bfi2xs', compact('questionnaireCampaignId', 'questionnaireId'));
    }

    public function preview()
    {
        return view('questionnaires-campaign.bfi2xs.bfi2xs');
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'questionnaireCampaignId' => ['required', 'integer'],
            'questionnaireId' => ['required', 'integer'],
            'q1' => ['required', 'integer'],
            'q2' => ['required', 'integer'],
            'q3' => ['required', 'integer'],
            'q4' => ['required', 'integer'],
            'q5' => ['required', 'integer'],
            'q6' => ['required', 'integer'],
            'q7' => ['required', 'integer'],
            'q8' => ['required', 'integer'],
            'q9' => ['required', 'integer'],
            'q10' => ['required', 'integer'],
            'q11' => ['required', 'integer'],
            'q12' => ['required', 'integer'],
            'q13' => ['required', 'integer'],
            'q14' => ['required', 'integer'],
            'q15' => ['required', 'integer'],
        ]);

        $questionnaireCampaignId = $validatedData['questionnaireCampaignId'];
        $questionnaireId = $validatedData['questionnaireId'];

        $questionnaireCampaign = QuestionnaireCampaign::findOrFail($questionnaireCampaignId);

        if ($questionnaireCampaign->state == "Live") {
            $alreadyAnswered = UserQuestionnaireAnswer::where([
                'user_id' => Auth::id(),
                'q_id' => $questionnaireId,
                'q_c_id' => $questionnaireCampaignId,
            ])->exists();

            if (!$alreadyAnswered) {
                $answer = BFI2XS::create($validatedData);

                $userAnswer = [
                    'user_id' => Auth::id(),
                    'q_id' => $questionnaireId,
                    'q_c_id' => $questionnaireCampaignId,
                    'answer_id' => $answer->id,
                ];

                UserQuestionnaireAnswer::create($userAnswer);

                $scales = $this->calculateScales($answer->id);
                foreach ($scales as $scaleName => $severityLevel) {
                    // Try to retrieve the HumanFactor or create a new one
                    $humanFactor = HumanFactor::firstOrCreate(['name' => $scaleName]);
            
                    // Create a record in UserHFThreat
                    UserHfThreat::create([
                        'user_id' => Auth::id(),
                        'hf_id' => $humanFactor->id,
                        'threat_id' => null,
                        'severityLevel' => $severityLevel,
                    ]);
                }


            } else {
                return back()->with('error', 'Already answered');
            }
        } else {
            return back()->with('error', 'Questionnaire campaign closed!');
        }

        return redirect()->route('questionnaires-campaign.index')->with('success', 'Questionnaire completed successfully!');
    }

    public function answer($userId, $questionnaireCampaignId, $questionnaireId)
    {
        $userQuestionnaireAnswer = UserQuestionnaireAnswer::where([
            ['user_id', '=', $userId],
            ['q_id', '=', $questionnaireId],
            ['q_c_id', '=', $questionnaireCampaignId],
        ])->firstOrFail();

        $id = $userQuestionnaireAnswer->answer_id;

        $bfi2xs = BFI2XS::findOrFail($id);

        $answers = [];
        for ($i = 1; $i <= 15; $i++) {
            $question = 'q' . $i;
            $answers[$question] = $bfi2xs->$question;
        }

        return view('questionnaires-campaign.bfi2xs.bfi2xs-result', compact('bfi2xs', 'answers'));
    }

    public function result($id)
    {
        $bfi2xs = BFI2XS::findOrFail($id);

        $answers = [];
        for ($i = 1; $i <= 15; $i++) {
            $question = 'q' . $i;
            $answers[$question] = $bfi2xs->$question;
        }

        $scales = $this->calculateScales($id);

        return view('questionnaires-campaign.bfi2xs.bfi2xs-result', compact('bfi2xs', 'answers', 'scales'));
    }

    public function calculateScales($id)
    {
        $bfi2xs = BFI2XS::findOrFail($id);
        // Reverse-scored items:
        $reverseItems = [1, 3, 7, 8, 10, 14];
        $maxScore = 5; // scores range

        // Extract responses and apply reverse scoring
        $responses = [];
        for ($i = 1; $i <= 15; $i++) {
            $question = 'q' . $i;
            $responses[$i] = in_array($i, $reverseItems) ? ($maxScore + 1 - $bfi2xs->$question) : $bfi2xs->$question;
        }

        // Calculate scales
        $scales = [
            'Extraversion' => ($responses[1] + $responses[6] + $responses[11]) / 3,
            'Agreeableness' => ($responses[2] + $responses[7] + $responses[12]) / 3,
            'Conscientiousness' => ($responses[3] + $responses[8] + $responses[13]) / 3,
            'Negative Emotionality' => ($responses[4] + $responses[9] + $responses[14]) / 3,
            'Open Mindedness' => ($responses[5] + $responses[10] + $responses[15]) / 3,
        ];

        return $scales;
    }

    public function testCalculateScales($id)
    {
        $scales = $this->calculateScales($id);

        return response()->json([
            'id' => $id,
            'scales' => $scales,
        ]);
    }


}

