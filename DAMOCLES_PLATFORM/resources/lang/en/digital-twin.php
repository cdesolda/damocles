<?php

return [
    'digitalTwins' => 'Digital Twins',
    'digitalTwinsCampaigns' => 'Digital Twins Campaigns',
    'digitalTwinsCampaignsAvailable' => 'Digital Twins Campaigns Available',
    'new' => 'New Digital Twins Campaign',
    'noDigitalTwinsCampaign' => 'No digital twins campaign available! Create a new one!',
    'digitalTwinCampaignDetails' => 'Digital Twins Campaign details',
    'title' => 'Title',
    'description' => 'Description',
    'defineUserPromptShort' => 'Define User Prompt',
    'defineThreatPromptShort' => 'Define Threat Prompt',
    'defineUserPrompt' => 'Customise the prompt for the profile definition (2/7)',
    'defineThreatPrompt' => 'Define the LLM Prompt for threat definition (3/7)',
    'newCampaign' => [
        'newTitle' => 'Define general information (1/7)',
        'title' => 'Title (max 255 characters)',
        'description' => 'Description',
        'threat' => 'Threat',
        'name' => 'Name',
        'surname' => 'Surname',
        'gender' => 'Gender',
        'dob' => 'Date of birth',
        'company_role' => 'Company role',
        'demographicAttributes' => 'Demographic attributes that caractherize the digital twin',
        'humanFactors' => 'Human Factors that caractherize the digital twin',
    ],
    'campaignState' => 'Campaign state',
    'deleteCampaign' => [
        'deleteTitle' => 'Delete digital twins campaign',
        'deleteMessage' => 'Are you sure to delete the digital twins campaign?'
    ],
    'deleteSimulation' => [
        'deleteTitle' => 'Delete digital twins simulation',
        'deleteMessage' => 'Are you sure to delete the digital twins simulation?'
    ],
    'demographicAttributes' => 'Demographic attributes',
    'noDemographicAttributes' => 'No demographic attributes',
    'noDemographicAttributesSelected' => 'No demographic attributes selected',
    'humanFactors' => 'Human Factors',
    'noHumanFactors' => 'No human factors',
    'noHumanFactorsSelected' => 'No human factors selected',
    'profilePrompt' => [
        'profilePrompt' => 'Define the LLM Prompt for Profile Definition',
        'openingSentence' => 'Imagine you are a user interacting with online systems.',
        'shortFirstSentence' => 'These are your characteristics:',
        'mediumFirstSentence' => 'Your profile is defined by the following characteristics:',
        'detailedFirstSentence' => 'Your profile is defined by the following characteristics:',
        'shortLastSentence' => '',
        'mediumLastSentence' => 'Consider these factors in shaping your online behaviour.',
        'detailedLastSentence' => 'These characteristics shape your behaviour in digital environments, including your perception of risk, your attention levels, and your ability to identify threat signals. Also consider any cognitive or behavioural vulnerabilities that are relevant to the type of attack you might experience.',
    ],
    'generate' => 'Generate Campaign',
    'typeOfPrompt' => [
        'typeOfPrompt' => 'Type of Prompt',
        'short' => 'Short lenght Prompt',
        'medium' => 'Medium lenght Prompt',
        'detailed' => 'Detailed lenght Prompt',
        'tooltip' => [
            'short' => 'Pros: 
            - Simplicity: Very quick to understand and apply. 
            - Efficiency: Minimises cognitive load for generating answers. 
            
            Cons: 
            - Less context: Lack of detailed guidelines may lead to less precise results.',

            'medium' => 'Pros: 
            - Greater detail: Provides additional context for more precise simulations.
            - Structure: Offers moderate guidance for output generation. 
            
            Cons: 
            - Balancing: less immediate than in the short version, but not yet fully detailed.',

            'detailed' => 'Pros: 
            - High accuracy: Ideal for detailed simulations and in-depth analysis. 
            - Customisation: Adapts to complex scenarios and allows full exploration of user behaviour. 
            
            Cons: 
            - Complexity: More challenging to understand and apply, both for the prompt creator and the model. 
            - Rigidity: Less flexible for cases requiring rapid adaptation.',
        ],
        'tooltipThreat' => [
            'short' => 'Pros: 
            - Simplicity: Very quick to understand and apply.
            - Flexibility: Suitable for a wide range of threats by simply changing the variable [TYPE_ OF_ATTACH].  
            - Efficiency: Minimises cognitive load for generating answers. 
            
            Cons: 
            - Generalisation: May not include sufficient detail for very specific simulations.  
            - Less context: Lack of detailed guidelines may lead to less precise results.',

            'medium' => 'Pros: 
            - Greater detail: Provides additional context for more precise simulations.
            - Structure: Offers moderate guidance for output generation. 
            - Applicability: Can be used with different types of threats while maintaining a medium level of detail. 
            
            Cons: 
            - Balancing: less immediate than in the short version, but not yet fully detailed.
            - Moderate flexibility: some particularly complex threats may require further customisation. ',

            'detailed' => 'Pros: 
            - High accuracy: Ideal for detailed simulations and in-depth analysis. 
            - Customisation: Adapts to complex scenarios and allows full exploration of user behaviour. 
            - Targeted applicability: Can be used to design specific countermeasures for a type of threat. 
            
            Cons: 
            - Complexity: More challenging to understand and apply, both for the prompt creator and the model. 
            - Rigidity: Less flexible for cases requiring rapid adaptation.',
        ],

    ],
    'chooseFakeUsersExplanation' => 'Select one or more Fake users to simulate the campaign',
    'simulation' => 'Simulate Ethical Phishing',
    'digitalTwinCampaignSimulation' => 'Digital Twin Campaign Simulation',
    'digitalTwinCampaignSimulationTitle' => 'Simulate the campaign of fake users before executing it on real users (5/7)',
    'generateSimulation' => 'Generate the simulation',
    'simulationResults' => 'Digital Twin Campaign Simulation: results',
    'simulationResultsTitle' => 'Digital Twin Campaign Simulation: results (6/7)',
    'threat' => 'Threat',
    'noThreats' => 'No Threats',
    'newSimulation' => [
        'value' => [
            'threat' => 'Select the threat that the Ethical Phishing operation targets.',
        ],
    ],
    'threatPrompt' => [
        'threatPrompt' => 'LLM Prompt for Threat Definition',
        'openingSentence' => 'Use the newly created profile to simulate the behaviour in response to an threat of type',
        'shortFirstSentence' => 'The threat took place via an e-mail with the following content:',
        'mediumFirstSentence' => 'The threat is conducted by means of an email or message with the following content:',
        'detailedFirstSentence' => 'The threat is conducted by means of an email or message with the following content:',
        'shortLastSentence' => '',
        'mediumLastSentence' => 'Analyse how the profile responds to this threat and what factors influence the behaviour.',
        'detailedLastSentence' => "
        1) Analyses how the profile responds to the threat and what characteristics influence behaviour. 
        2) Identify any vulnerabilities exploited by the threat. 
        3) Describe how the profile could improve its ability to recognise and prevent similar threats.  
            Provide a detailed analysis of the profile’s decision-making process and simulation outcomes.",
    ],
    'digitalTwinsSimulations' => 'Digital Twins Simulations',
    'noDigitalTwinsSimulations' => 'No digital twins simulation present! Create a new one!',
    'analyse' => 'Analyse results',
    'detailsAnalyse' => 'Detailed analysis',
    'analyseUser' => 'Analyse user results',
    'analyseBy' => 'Analyse results by',
    'executeOnNewUsers' => 'Execute campaign on new users',
    'simulationAnalyses' => 'Simulation analyses',
    'downloadModalMessage' => 'Are you sure you want to download the data related to the digital twins campaign simulation?',
    'executeDigitalTwinCampaign' => 'Execute Digital Twin Campaign',
    'executeCampaign' => 'Execute Campaign',
    'inProgress' => 'Campaign in progress',
    'completed' => 'Campaign Completed',
    'executeFirst' => 'Execute First!',
    'allDone' => 'All results already obtained',
    'noUserFound' => 'No results found for the selected user. Please ensure they have been added to the campaign',
    'noEmails' => 'No phishing emails recorded.',
];
