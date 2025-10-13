<?php

use App\Http\Controllers\HAISController;
use App\Http\Controllers\DAMOCLESController;
use App\Http\Controllers\PhishingEmailGroupController;
use App\Http\Controllers\StPIIBController;
use App\Http\Controllers\BFI2XSController;
use App\Http\Controllers\TEIQueSFController;
use App\Http\Controllers\LLMController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ThreatController;
use App\Http\Controllers\HumanFactorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuestionnaireCampaignController;
use App\Http\Controllers\PhishingCampaignController;
use App\Http\Controllers\PhishingTopicController;
use App\Http\Controllers\PhishingPersuasionController;
use App\Http\Controllers\PhishingEmotionalTriggerController;
use App\Http\Controllers\PhishingEmailController;
use App\Http\Controllers\DigitalTwinCampaignController;
use App\Http\Controllers\TrainingCampaignController;
use App\Http\Controllers\TrainingPromptController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/privacy-policy', function () {
    return view('policy.privacy-policy');
})->name('privacy-policy');

Route::get('/cookie-policy', function () {
    return view('policy.cookie-policy');
})->name('cookie-policy');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/llms', [LLMController::class, 'index'])->name('llms.index');
        Route::post('/llm/create', [LLMController::class, 'create'])->name('llm.create');
        Route::put('/llm/{llm}', [LLMController::class, 'update'])->name('llm.update');
        Route::delete('/llm/{llm}', [LLMController::class, 'destroy'])->name('llm.destroy');
        Route::patch('/profile/updateFromAdmin', [UserController::class, 'updateFromAdmin'])->name('profile.updateFromAdmin');
        Route::delete('/profile/{id}', [UserController::class, 'destroyFromAdmin'])->name('profile.destroyFromAdmin');
        Route::get('/users/{role?}', [UserController::class, 'index'])
            ->middleware(['auth', 'verified'])
            ->name('users');
        Route::post('/user/accept', [UserController::class, 'updateAccept'])->name('user.updateAccept');
        Route::get('/user/active/{user}', [UserController::class, 'updateActive'])->name('user.updateActive');
    });

    Route::middleware(['role:Evaluator', 'checkActive'])->group(function () {
        // Digital Twins campaigns
        Route::get('/digital-twins-campaign', [DigitalTwinCampaignController::class, 'index'])->name('digital-twins.index');
        Route::get('/digital-twins-campaign/new', [DigitalTwinCampaignController::class, 'new'])->name('digital-twins.new');
        Route::match(['GET', 'POST'], '/digital-twins-campaign/new/define-user-prompt', [DigitalTwinCampaignController::class, 'defineUserPrompt'])->name('digital-twins.defineUserPrompt');
        Route::match(['GET', 'POST'], '/digital-twins-campaign/new/define-attach-prompt', [DigitalTwinCampaignController::class, 'defineAttachPrompt'])->name('digital-twins.defineAttachPrompt');
        Route::match(['GET', 'POST'], '/digital-twins-campaign/new/simulation', [DigitalTwinCampaignController::class, 'simulation'])->name('digital-twins.simulation');
        Route::match(['GET', 'POST'], '/digital-twins-campaign/new/simulation-result', [DigitalTwinCampaignController::class, 'simulationResult'])->name('digital-twins.simulationResult');
        Route::match(['GET', 'POST'], '/digital-twins-campaign/new/email-choice', [DigitalTwinCampaignController::class, 'chooseEmail'])->name('digital-twins.chooseEmail');
        Route::match(['GET', 'POST'], '/digital-twins-campaign/new/users-choice', [DigitalTwinCampaignController::class, 'chooseUsers'])->name('digital-twins.chooseUsers');
        Route::post('/digital-twins-campaign/new/finalize-creation', [DigitalTwinCampaignController::class, 'finalizeCreation'])->name('digital-twins.finalize');

        Route::get('/digital-twins-campaign/detail/{digitalTwinsCampaign}', [DigitalTwinCampaignController::class, 'details'])->name('digital-twins.details');
        Route::delete('/digital-twins-campaign/delete/{digitalTwinsCampaign}', [DigitalTwinCampaignController::class, 'destroy'])->name('digital-twins.destroy');
        Route::get('/digital-twins-campaign/detail/{digitalTwinsCampaign}/add-users', [DigitalTwinCampaignController::class, 'addUsers'])->name('digital-twins.addUsers');
        Route::get('/digital-twins-campaign/detail/{digitalTwinsCampaign}/execute', [DigitalTwinCampaignController::class, 'executeCampaign'])->name('digital-twins.executeCampaign');
        Route::match(['GET', 'POST'], '/digital-twins-campaign/{digitalTwinsCampaign}/execute-campaign', [DigitalTwinCampaignController::class, 'executeDigitalTwinCampaign'])->name('digital-twins.executeDigitalTwinCampaign');
        Route::post('/digital-twins-campaign/detail/{digitalTwinsCampaign}/finalize-add-users', [DigitalTwinCampaignController::class, 'finalizeAddUsers'])->name('digital-twins.finalizeAddUsers');

        // Digital Twins campaigns - results
        Route::get('/digital-twins-campaign/{digitalTwinsCampaign}/download-data', [DigitalTwinCampaignController::class, 'downloadDataCSV'])->name('digital-twins.download-data-csv');
        Route::get('/digital-twins-campaign/{digitalTwinsCampaign}/analyse-results', [DigitalTwinCampaignController::class, 'analyse'])->name('digital-twins.analyse');
        Route::get('/digital-twins-campaign/{digitalTwinsCampaign}/analyse-user/{user}', [DigitalTwinCampaignController::class, 'analyseUser'])->name('digital-twins.analyseUser');

        //Emails Group
        Route::get('/phishing-email-groups', [PhishingEmailGroupController::class, 'index'])->name('email-groups.index');
        Route::get('/phishing-email-groups/new', [PhishingEmailGroupController::class, 'new'])->name('email-groups.new');
        Route::post('/phishing-email-groups/save', [PhishingEmailGroupController::class, 'save'])->name('email-groups.save');
        Route::delete('/phishing-email-groups/delete/{group}', [PhishingEmailGroupController::class, 'destroy'])->name('email-groups.destroy');
        Route::get('/phishing-email-groups/duplicate/{group}', [PhishingEmailGroupController::class, 'duplicate'])->name('email-groups.duplicate');
        Route::get('/phishing-email-groups/edit/{group}', [PhishingEmailGroupController::class, 'edit'])->name('email-groups.edit');
        Route::post('/phishing-email-groups/update', [PhishingEmailGroupController::class, 'updateGroup'])->name('email-groups.update-group');

        // Emails
        Route::get('/phishing-emails', [PhishingEmailController::class, 'index'])->name('emails.index');
        Route::get('/phishing-emails/new', [PhishingEmailController::class, 'new'])->name('emails.new');
        Route::post('/phishing-emails/generate', [PhishingEmailController::class, 'generate'])->name('emails.generate');
        
        Route::post('/phishing-emails/extract-topic', [PhishingCampaignController::class, 'extractTopic'])->name('emails.extractTopic');
        Route::post('/phishing-emails/add-details-email-example', [PhishingCampaignController::class, 'addDetailsEmailExample'])->name('emails.addDetailsEmailExample');
        Route::post('/phishing-emails/rewrite', [PhishingCampaignController::class, 'rewriteEmail'])->name('emails.rewrite');
        
        Route::post('/phishing-emails/save', [PhishingEmailController::class, 'savePhishingEmails'])->name('emails.save-emails');
        Route::get('/phishing-emails/edit/{email}', [PhishingEmailController::class, 'edit'])->name('emails.edit');
        Route::post('/phishing-emails/update', [PhishingEmailController::class, 'updatePhishingEmails'])->name('emails.update-emails');
        Route::get('/phishing-emails/duplicate/{email}', [PhishingEmailController::class, 'duplicate'])->name('emails.duplicate');
        Route::delete('/phishing-emails/multiple-delete/{emails}', [PhishingEmailController::class, 'multipleDestroy'])->name('emails.multiple.destroy');

        // Questionnaires campaigns
        Route::get('/questionnaires', [QuestionnaireCampaignController::class, 'questionnaires'])->name('questionnaires');
        Route::get('/questionnaires-campaign', [QuestionnaireCampaignController::class, 'index'])->name('questionnaires-campaign.index');

        Route::get('/questionnaires-campaign/new', [QuestionnaireCampaignController::class, 'new'])->name('questionnaires-campaign.new');
        Route::post('/questionnaire-campaign/create', [QuestionnaireCampaignController::class, 'create'])->name('questionnaire-campaign.create');

        Route::get('/questionnaire-campaign/questionnaires/{questionnaireCampaign}', [QuestionnaireCampaignController::class, 'questionnairesQuestionnaireCampaign'])->name('questionnaire-campaign.questionnaires');
        Route::post('/questionnaire-campaign/questionnaires-questionnaire-campaign', [QuestionnaireCampaignController::class, 'saveQuestionnairesQuestionnaireCampaign'])->name('questionnaire-campaign.questionnaires-questionnaire-campaign');

        Route::get('/questionnaire-campaign/users/{questionnaireCampaign}', [QuestionnaireCampaignController::class, 'users'])->name('questionnaire-campaign.users');
        Route::post('/questionnaire-campaign/users-questionnaire', [QuestionnaireCampaignController::class, 'saveUsersQuestionnaireCampaign'])->name('questionnaire-campaign.users-questionnaire-campaign');

        Route::post('/questionnaire-campaign/change-state/{questionnaireCampaign}/{state}', [QuestionnaireCampaignController::class, 'changeState'])->name('questionnaire-campaign.change-state');
        Route::get('/questionnaire-campaign/analyse/{questionnaireCampaign}', [QuestionnaireCampaignController::class, 'analyse'])->name('questionnaire-campaign.analyse');
        Route::get('/questionnaire-campaign/stop/{questionnaireCampaign}', [QuestionnaireCampaignController::class, 'stop'])->name('questionnaire-campaign.stop');
        Route::get('/questionnaire-campaign/detail/{questionnaireCampaign}', [QuestionnaireCampaignController::class, 'details'])->name('questionnaire-campaign.details');
        Route::get('/questionnaire-campaign/duplicate/{questionnaireCampaign}', [QuestionnaireCampaignController::class, 'duplicate'])->name('questionnaire-campaign.duplicate');
        Route::delete('/questionnaire-campaign/delete/{questionnaireCampaign}', [QuestionnaireCampaignController::class, 'destroy'])->name('questionnaire-campaign.destroy');

        // Questionnaires
        Route::delete('/result/{answer}', [QuestionnaireCampaignController::class, 'deleteAnswer'])->name('result.destroy');

        // HAIS
        Route::get('/hais', [HAISController::class, 'preview'])->name('hais.preview');
        Route::get('/hais/result/{hais}', [HAISController::class, 'result'])->name('hais.result');
        //Route::delete('/hais/result/{hais}', [HAISController::class, 'destroy'])->name('hais.destroy');

        // DAMOCLES
        Route::get('/damocles', [DAMOCLESController::class, 'preview'])->name('damocles.preview');
        Route::get('/damocles/result/{damocles}', [DAMOCLESController::class, 'result'])->name('damocles.result');
        //Route::delete('/damocles/result/{damocles}', [DAMOCLESController::class, 'destroy'])->name('damocles.destroy');

        // StP–II–B
        Route::get('/susceptibility-to-persuasion-ii', [StPIIBController::class, 'preview'])->name('susceptibility-to-persuasion-ii.preview');
        Route::get('/susceptibility-to-persuasion-ii/result/{susceptibility_to_persuasion_ii}', [StPIIBController::class, 'result'])->name('susceptibility-to-persuasion-ii.result');

        // BFI-2-XS
        Route::get('/big-five-inventory', [BFI2XSController::class, 'preview'])->name('big-five-inventory.preview');
        Route::get('/big-five-inventory/result/{big_five_inventory}', [BFI2XSController::class, 'result'])->name('big-five-inventory.result');

        // TEIQue-SF 
        Route::get('/trait-emotional-intelligence', [TEIQueSFController::class, 'preview'])->name('trait-emotional-intelligence.preview');
        Route::get('/trait-emotional-intelligence/result/{trait_emotional_intelligence}', [TEIQueSFController::class, 'result'])->name('trait-emotional-intelligence.result');

        // Phishing campaigns
        Route::get('/phishing-campaign', [PhishingCampaignController::class, 'index'])->name('phishing-campaign.index');

        Route::get('/phishing-campaign/new', [PhishingCampaignController::class, 'new'])->name('phishing-campaign.new');
        Route::post('/phishing-campaign/create', [PhishingCampaignController::class, 'create'])->name('phishing-campaign.create');

        Route::get('/phishing-campaign/add-details/{phishingCampaign}', [PhishingCampaignController::class, 'addDetails'])->name('phishing-campaign.add-details');
        Route::post('/phishing-campaign/add-details', [PhishingCampaignController::class, 'saveDetails'])->name('phishing-campaign.save-details');

        Route::get('/phishing-campaign/emails/{phishingCampaign}', [PhishingCampaignController::class, 'generate'])->name('phishing-campaign.generate-emails');
        Route::post('/phishing-campaign/emails', [PhishingCampaignController::class, 'savePhishingEmails'])->name('phishing-campaign.save-emails');

        Route::get('/phishing-campaign/users/{phishingCampaign}', [PhishingCampaignController::class, 'users'])->name('phishing-campaign.users');
        Route::post('/phishing-campaign/users-phishing-email', [PhishingCampaignController::class, 'saveUsersPhishingEmails'])->name('phishing-campaign.users-phishing-email');

        Route::post('/phishing-campaign/change-state/{phishingCampaign}/{state}', [PhishingCampaignController::class, 'changeState'])->name('phishing-campaign.change-state');
        Route::get('/phishing-campaign/analyse/{phishingCampaign}', [PhishingCampaignController::class, 'analyse'])->name('phishing-campaign.analyse');
        Route::get('/phishing-campaign/stop/{phishingCampaign}', [PhishingCampaignController::class, 'stop'])->name('phishing-campaign.stop');
        Route::get('/phishing-campaign/detail/{phishingCampaign}', [PhishingCampaignController::class, 'details'])->name('phishing-campaign.details');
        Route::get('/phishing-campaign/duplicate/{phishingCampaign}', [PhishingCampaignController::class, 'duplicate'])->name('phishing-campaign.duplicate');
        Route::delete('/phishing-campaign/delete/{phishingCampaign}', [PhishingCampaignController::class, 'destroy'])->name('phishing-campaign.destroy');

        Route::get('/phishing-campaign/download-data/{phishingCampaign}', [PhishingCampaignController::class, 'downloadDataCSV'])->name('phishing-campaign.download-data-csv');
        Route::get('/phishing-campaign/download-all-data', [PhishingCampaignController::class, 'downloadAllDataCSV'])->name('phishing-campaign.download-all-data-csv');

        // Phishing email attributes
        Route::get('/phishing-campaign/option', [PhishingCampaignController::class, 'option'])->name('phishing-campaign.option');
        Route::post('/phishing-campaign/topic', [PhishingTopicController::class, 'create'])->name('topic.create');
        Route::delete('/phishing-campaign/topic/{topic}', [PhishingTopicController::class, 'destroy'])->name('topic.destroy');

        Route::post('/phishing-campaign/persuasion', [PhishingPersuasionController::class, 'create'])->name('persuasion.create');
        Route::delete('/phishing-campaign/persuasion/{persuasion}', [PhishingPersuasionController::class, 'destroy'])->name('persuasion.destroy');

        Route::post('/phishing-campaign/emotional-trigger', [PhishingEmotionalTriggerController::class, 'create'])->name('emotional-trigger.create');
        Route::delete('/phishing-campaign/emotional-trigger/{emotionalTrigger}', [PhishingEmotionalTriggerController::class, 'destroy'])->name('emotional-trigger.destroy');

        // Extended User profiles attributes
        Route::post('/phishing-campaign/threat', [ThreatController::class, 'create'])->name('threat.create');
        Route::delete('/phishing-campaign/threat/{threat}', [ThreatController::class, 'destroy'])->name('threat.destroy');

        Route::post('/phishing-campaign/humanFactor', [HumanFactorController::class, 'create'])->name('humanFactor.create');
        Route::delete('/phishing-campaign/humanFactor/{humanfactor}', [HumanFactorController::class, 'destroy'])->name('humanFactor.destroy');

        //Training campaign
        Route::get('/training-campaign', [TrainingCampaignController::class, 'index'])->name('training-campaign.index');

        Route::get('/training-campaign/new', [TrainingCampaignController::class, 'new'])->name('training-campaign.new');
        Route::post('/training-campaign/create', [TrainingCampaignController::class, 'create'])->name('training-campaign.create');

        Route::get('/training-campaign/simulation/{trainingCampaign}', [TrainingCampaignController::class, 'simulation'])->name('training-campaign.simulation');
        Route::post('/training-campaign/simulation', [TrainingCampaignController::class, 'saveSimulation'])->name('training-campaign.save-simulation');

        Route::post('/training-campaign/trainings/generate', [TrainingCampaignController::class, 'generateTrainings'])->name('training-campaign.generate-trainings');

        Route::get('/training-campaign/users/{trainingCampaign}', [TrainingCampaignController::class, 'users'])->name('training-campaign.users');
        Route::post('/training-campaign/users-training-email', [TrainingCampaignController::class, 'saveUsersTraining'])->name('training-campaign.users-training');

        Route::post('/training-campaign/change-state/{trainingCampaign}/{state}', [TrainingCampaignController::class, 'changeState'])->name('training-campaign.change-state');
        Route::get('/training-campaign/analyse/{trainingCampaign}', [TrainingCampaignController::class, 'analyse'])->name('training-campaign.analyse');
        Route::get('/training-campaign/stop/{trainingCampaign}', [TrainingCampaignController::class, 'stop'])->name('training-campaign.stop');
        Route::get('/training-campaign/detail/{trainingCampaign}', [TrainingCampaignController::class, 'details'])->name('training-campaign.details');
        Route::get('/training-campaign/duplicate/{trainingCampaign}', [TrainingCampaignController::class, 'duplicate'])->name('training-campaign.duplicate');
        Route::delete('/training-campaign/delete/{trainingCampaign}', [TrainingCampaignController::class, 'destroy'])->name('training-campaign.destroy');

        Route::get('/training-campaign/download-data/{trainingCampaign}', [TrainingCampaignController::class, 'downloadDataCSV'])->name('training-campaign.download-data-csv');
        Route::get('/training-campaign/download-all-data', [TrainingCampaignController::class, 'downloadAllDataCSV'])->name('training-campaign.download-all-data-csv');

        // Training email attributes
        Route::get('/training-campaign/option', [TrainingCampaignController::class, 'option'])->name('training-campaign.option');
        Route::post('/training-campaign/prompt', [TrainingPromptController::class, 'create'])->name('prompt.create');
        Route::delete('/training-campaign/prompt/{prompt}', [TrainingPromptController::class, 'destroy'])->name('prompt.destroy');
        Route::post('/training-campaign/fake-user', [UserController::class, 'createFakeUser'])->name('fake-user.create');
        Route::delete('/training-campaign/fake-user/{user}', [UserController::class, 'destroyFakeUser'])->name('fake-user.destroy');
    });

    Route::middleware(['role:Admin,Evaluator,User'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::middleware(['role:User', 'checkActive'])->group(function () {
        // Questionnaires campaigns
        Route::get('/questionnaires-campaign', [QuestionnaireCampaignController::class, 'index'])->name('questionnaires-campaign.index');
        Route::get('/questionnaires-campaign/{questionnaireCampaign}', [QuestionnaireCampaignController::class, 'joinQuestionnaireCampaign'])->name('questionnaires-campaign.questionnaire-campaign-join');

        // HAIS
        Route::post('/hais', [HAISController::class, 'create'])->name('hais.create');
        Route::get('/hais/answer/{user}/{questionnaireCampaign}/{questionnaire}', [HAISController::class, 'answer'])->name('hais.answer');

        // DAMOCLES
        Route::post('/damocles', [DAMOCLESController::class, 'create'])->name('damocles.create');
        Route::get('/damocles/answer/{user}/{questionnaireCampaign}/{questionnaire}', [DAMOCLESController::class, 'answer'])->name('damocles.answer');

        // StP–II–B
        Route::post('/susceptibility-to-persuasion-ii', [StPIIBController::class, 'create'])->name('susceptibility-to-persuasion-ii.create');
        Route::get('/susceptibility-to-persuasion-ii/answer/{user}/{questionnaireCampaign}/{questionnaire}', [StPIIBController::class, 'answer'])->name('susceptibility-to-persuasion-ii.answer');

        // BFI-2-XS
        Route::post('/big-five-inventory', [BFI2XSController::class, 'create'])->name('big-five-inventory.create');
        Route::get('/big-five-inventory/answer/{user}/{questionnaireCampaign}/{questionnaire}', [BFI2XSController::class, 'answer'])->name('big-five-inventory.answer');

        // TEIQue-SF
        Route::post('/trait-emotional-intelligence', [TEIQueSFController::class, 'create'])->name('trait-emotional-intelligence.create');
        Route::get('/trait-emotional-intelligence/answer/{user}/{questionnaireCampaign}/{questionnaire}', [TEIQueSFController::class, 'answer'])->name('trait-emotional-intelligence.answer');
    });

    Route::middleware(['role:Evaluator,User', 'checkActive'])->group(function () {
        // Questionnaires campaigns
        Route::get('/questionnaires-campaign', [QuestionnaireCampaignController::class, 'index'])->name('questionnaires-campaign.index');

        // HAIS
        Route::get('/hais/{questionnaireCampaign}/{questionnaire}', [HAISController::class, 'index'])->name('hais.index');

        // DAMOCLES
        Route::get('/damocles/{questionnaireCampaign}/{questionnaire}', [DAMOCLESController::class, 'index'])->name('damocles.index');

        // StP–II–B
        Route::get('/susceptibility-to-persuasion-ii/{questionnaireCampaign}/{questionnaire}', [StPIIBController::class, 'index'])->name('susceptibility-to-persuasion-ii.index');

        // BFI-2-XS
        Route::get('/big-five-inventory/{questionnaireCampaign}/{questionnaire}', [BFI2XSController::class, 'index'])->name('big-five-inventory.index');

        // TEIQue-SF
        Route::get('/trait-emotional-intelligence/{questionnaireCampaign}/{questionnaire}', [TEIQueSFController::class, 'index'])->name('trait-emotional-intelligence.index');

        // Training campaigns
        Route::get('/training-campaign', [TrainingCampaignController::class, 'index'])->name('training-campaign.index');
        Route::get('/training-campaign/generate-training/{user}/{trainingCampaign}', [TrainingCampaignController::class, 'generateUserTraining'])->name('training-campaign.generateUserTraining');
        Route::get('/training-campaign/generate-test-training/{userTrainingCampaign}', [TrainingCampaignController::class, 'generateTestUserTraining'])->name('training-campaign.generateTestUserTraining');
        Route::get('/training-campaign/done-training/{userTrainingCampaign}', [TrainingCampaignController::class, 'doneUserTraining'])->name('training-campaign.doneUserTraining');
    });
});

// Endpoint for the link opened in the email
Route::get('/opened/{id}', [PhishingCampaignController::class, 'opened'])->name('phishing.campaign.opened');

// Endpoint for the link clicked in the email
Route::get('/clicked/{id}', [PhishingCampaignController::class, 'clicked'])->name('phishing.campaign.clicked');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


//TODO: remove in the future
//route to test the calculation of scores

//Route::get('/bfi2xs/scales/{id}', [BFI2XSController::class, 'testCalculateScales']);
//Route::get('/stpiib/scales/{id}', [StPIIBController::class, 'testCalculateScales']);
//Route::get('/teiquesf/scales/{id}', [TEIQueSFController::class, 'testCalculateScales']);