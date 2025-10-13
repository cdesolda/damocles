<?php

namespace App\Http\Controllers;

use App\Models\DigitalTwinsCampaign;
use App\Models\User;
use App\Models\LLM;
use App\Models\Questionnaire;
use App\Models\QuestionnaireCampaign;
use App\Models\PhishingCampaign;
use App\Models\TrainingCampaign;
use App\Models\UserQuestionnaireCampaign;
use App\Models\UserTrainingCampaign;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $adminsCount = User::where('role', 'Admin')->get()->count();
        $evaluatorsCount = User::where('role', 'Evaluator')->get()->count();
        $usersCount = User::where('role', 'User')->where('type', 'Real')->get()->count();

        $llmsCount = LLM::all()->count();

        $phishingCampaigns = [];
        $questionnairesCampaigns = [];
        $totalTrainingCampaigns = [];
        $digitalTwinsCampaigns = [];

        if ($user->role === 'Admin') {
            $phishingCampaigns = PhishingCampaign::all();
            $questionnairesCampaigns = QuestionnaireCampaign::all();
            $trainingCampaigns = TrainingCampaign::all();
            $digitalTwinsCampaigns = DigitalTwinsCampaign::all();
        } elseif ($user->role === 'Evaluator') {
            $phishingCampaigns = PhishingCampaign::where('evaluator_id', $user->id)->get();
            $questionnairesCampaigns = QuestionnaireCampaign::where('evaluator_id', $user->id)->get();
            $trainingCampaigns = TrainingCampaign::where('evaluator_id', $user->id)->get();
            $digitalTwinsCampaigns = DigitalTwinsCampaign::where('evaluator_id', $user->id)->get();
        } elseif ($user->role === 'User') {
            $questionnairesCampaigns = UserQuestionnaireCampaign::where('user_id', $user->id)->get();
            $trainingCampaigns = UserTrainingCampaign::where('user_id', $user->id)->get();

            $totalQuestionnaireCampaigns = $questionnairesCampaigns->count();
            $totalTrainingCampaigns = $trainingCampaigns->count();
            return view('dashboard.dashboard', compact('totalQuestionnaireCampaigns', 'totalTrainingCampaigns'));
        }

        $questionnairesCount = Questionnaire::all()->count();
        $totalPhishingCampaigns = $phishingCampaigns->count();
        $totalQuestionnaireCampaigns = $questionnairesCampaigns->count();
        $totalTrainingCampaigns = $trainingCampaigns->count();
        $totalDigitalTwinsCampaigns = $digitalTwinsCampaigns->count();

        if ($phishingCampaigns->isNotEmpty()) {
            $phishingCampaignsDraft = $phishingCampaigns->where('state', 'Draft')->count();
            $phishingCampaignsReady = $phishingCampaigns->where('state', 'Ready')->count();
            $phishingCampaignsLive = $phishingCampaigns->where('state', 'Live')->count();
            $phishingCampaignsCompleted = $phishingCampaigns->where('state', 'Completed')->count();
        } else {
            $phishingCampaignsDraft = 0;
            $phishingCampaignsReady = 0;
            $phishingCampaignsLive = 0;
            $phishingCampaignsCompleted = 0;
        }

        if ($questionnairesCampaigns->isNotEmpty()) {
            $questionnairesCampaignsDraft = $questionnairesCampaigns->where('state', 'Draft')->count();
            $questionnairesCampaignsReady = $questionnairesCampaigns->where('state', 'Ready')->count();
            $questionnairesCampaignsLive = $questionnairesCampaigns->where('state', 'Live')->count();
            $questionnairesCampaignsCompleted = $questionnairesCampaigns->where('state', 'Completed')->count();
        } else {
            $questionnairesCampaignsDraft = 0;
            $questionnairesCampaignsReady = 0;
            $questionnairesCampaignsLive = 0;
            $questionnairesCampaignsCompleted = 0;
        }

        if ($trainingCampaigns->isNotEmpty()) {
            $trainingCampaignsDraft = $trainingCampaigns->where('state', 'Draft')->count();
            $trainingCampaignsReady = $trainingCampaigns->where('state', 'Ready')->count();
            $trainingCampaignsLive = $trainingCampaigns->where('state', 'Live')->count();
            $trainingCampaignsCompleted = $trainingCampaigns->where('state', 'Completed')->count();
        } else {
            $trainingCampaignsDraft = 0;
            $trainingCampaignsReady = 0;
            $trainingCampaignsLive = 0;
            $trainingCampaignsCompleted = 0;
        }

        return view('dashboard.dashboard', compact(
            'adminsCount',
            'evaluatorsCount',
            'usersCount',
            'llmsCount',
            'totalPhishingCampaigns',
            'phishingCampaignsDraft',
            'phishingCampaignsReady',
            'phishingCampaignsLive',
            'phishingCampaignsCompleted',
            'questionnairesCount',
            'totalQuestionnaireCampaigns',
            'totalDigitalTwinsCampaigns',
            'questionnairesCampaignsDraft',
            'questionnairesCampaignsReady',
            'questionnairesCampaignsLive',
            'questionnairesCampaignsCompleted',
            'totalTrainingCampaigns',
            'trainingCampaignsDraft',
            'trainingCampaignsReady',
            'trainingCampaignsLive',
            'trainingCampaignsCompleted'
        ));
    }
}
