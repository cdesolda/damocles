<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StPIIB;
use App\Models\Questionnaire;
use App\Models\UserQuestionnaireAnswer;
use App\Models\QuestionnaireCampaign;
use App\Models\UserHfThreat;
use App\Models\HumanFactor;
use App\Models\Threat;

class StPIIBController extends Controller
{
    public function index($questionnaireCampaignId, $questionnaireId)
    {
        $questionnaireCampaign = QuestionnaireCampaign::findOrFail($questionnaireCampaignId);
        $questionnaire = Questionnaire::findOrFail($questionnaireId);

        return view('questionnaires-campaign.stp-ii-b.stp-ii-b', compact('questionnaireCampaignId', 'questionnaireId'));
    }

    public function preview()
    {
        return view('questionnaires-campaign.stp-ii-b.stp-ii-b');
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
            'q16' => ['required', 'integer'],
            'q17' => ['required', 'integer'],
            'q18' => ['required', 'integer'],
            'q19' => ['required', 'integer'],
            'q20' => ['required', 'integer'],
            'q21' => ['required', 'integer'],
            'q22' => ['required', 'integer'],
            'q23' => ['required', 'integer'],
            'q24' => ['required', 'integer'],
            'q25' => ['required', 'integer'],
            'q26' => ['required', 'integer'],
            'q27' => ['required', 'integer'],
            'q28' => ['required', 'integer'],
            'q29' => ['required', 'integer'],
            'q30' => ['required', 'integer'],
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

            // Check if the user has already answered
            if (!$alreadyAnswered) {
                $answer = StPIIB::create($validatedData);

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

        $stp_ii_b = StPIIB::findOrFail($id);

        $answers = [];
        for ($i = 1; $i <= 30; $i++) {
            $question = 'q' . $i;
            $answers[$question] = $stp_ii_b->$question;
        }

        return view('questionnaires-campaign.stp-ii-b.stp-ii-b-result', compact('stp_ii_b', 'answers'));
    }

    public function result($id)
    {
        $stp_ii_b = StPIIB::findOrFail($id);

        $answers = [];
        for ($i = 1; $i <= 30; $i++) {
            $question = 'q' . $i;
            $answers[$question] = $stp_ii_b->$question;
        }

        $scales = $this->calculateScales($id);

        return view('questionnaires-campaign.stp-ii-b.stp-ii-b-result', compact('stp_ii_b', 'answers', 'scales'));
    }

    public function calculateScales($id)
    {
        $stp_ii_b = StPIIB::findOrFail($id);
        // Reverse-scored items:
        $reverseItems = [16,17,18];
        $maxScore = 7; // scores range

        // Extract responses and apply reverse scoring
        $responses = [];
        for ($i = 1; $i <= 30; $i++) {
            $question = 'q' . $i;
            $responses[$i] = in_array($i, $reverseItems) ? ($maxScore + 1 - $stp_ii_b->$question) : $stp_ii_b->$question;
        }

        // Calculate scales
        $scales = [
            'Lack of premeditation' => ($responses[1] + $responses[2] + $responses[3]) / 3,
            'Need for consistency' => ($responses[4] + $responses[5] + $responses[6]) / 3,
            'Sensation seeking' => ($responses[7] + $responses[8] + $responses[9]) / 3,
            'Lack of self-control' => ($responses[10] + $responses[11] + $responses[12]) / 3,
            'Social influence' => ($responses[13] + $responses[14] + $responses[15]) / 3,
            'Need for avoidance of similarity' => ($responses[16] + $responses[17] + $responses[18]) / 3,
            'Risk preferences' => ($responses[19] + $responses[20] + $responses[21]) / 3,
            'Positive attitudes towards advertising' => ($responses[22] + $responses[23] + $responses[24]) / 3,
            'Need for cognition' => ($responses[25] + $responses[26] + $responses[27]) / 3,
            'Need for uniqueness' => ($responses[28] + $responses[29] + $responses[30]) / 3,
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
