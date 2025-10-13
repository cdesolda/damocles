<?php

return [
    'subject' => 'Subject',
    'body' => 'Body',
    'emails' => 'Emails',
    'email' => 'Email',
    'topic' => 'Topic',
    'persuasions' => 'Persuasions',
    'emotionalTriggers' => 'Emotional triggers',
    'emailAvailable' => 'Emails available',
    'new' => 'New Emails',
    'creation' => 'New Email creation (1/2)',
    'endCreation' => 'End creation',
    'newEmail' => [
        'mainTopic' => 'Main topic for the email (max 255 characters)',
        'topicDetails' => 'Description of the main topic',
    ],
    'edit' => 'Edit Email',
    'generate' => 'Generate email',
    'noEmail' => 'No emails available! Create new ones!',
    'modals' => [
        'duplicate' => 'Duplicate email',
        'duplicateMessage' => 'Are you sure to duplicate the email?',
        'delete' => 'Delete email',
        'deleteMessage' => 'Are you sure to delete the email?',
        'delete-emails' => 'Delete emails',
        'deleteMessage-emails' => 'Are you sure to delete the emails selected?',
    ],
    'deleteSucc' => 'Email deleted successfully!',
    'deleteSelected' => 'Delete selected emails',
    'updateSucc' => 'Email updated successfully!',
    'duplicateSucc' => 'Email duplicated successfully!',
    'groups' => [
        'selectGroup' => 'Select Email Group',
        'noGroups' => 'No Email Groups, create one in the emails group page!',
        'noGroupsShort' => 'No email groups created',
        'showEmailGroups' => 'Show email groups',
        'emailGroups' => 'Email Groups',
        'emailGroupsAvailable' => 'Email Groups available',
        'new' => 'New Email Group',
        'creation' => 'New Email Group creation',
        'name' => 'Email Group name',
        'newEmailGroup' => [
            'value' => [
                'name' => 'Name of the Email Group',
                'emails' => 'Select the emails to add to the Email Group',
            ],
        ],
        'edit' => 'Edit Email Group',
        'create' => 'Create Email Group',
        'noEmailGroup' => 'No Email Groups available! Create new one!',
        'modals' => [
            'duplicate' => 'Duplicate Email Group',
            'duplicateMessage' => 'Are you sure to duplicate the Email Group?',
            'delete' => 'Delete Email Group',
            'deleteMessage' => 'Are you sure to delete the Email Group?',
            'delete-emails' => 'Delete emails from the Email Group',
            'deleteMessage-emails' => 'Are you sure to delete the emails selected from the Email Group?',
        ],
        'deleteSucc' => 'Email Group deleted successfully!',
        'deleteSelected' => 'Delete selected emails',
        'updateSucc' => 'Group updated successfully!',
    ],
    'filterBy' => [
        'topic' => 'Filter by topic',
        'persuasion' => 'Filter by persuasions',
        'emotionalTrigger' => 'Filter by emotional triggers',
        'group' => 'Filter by group'
    ],
    'noEmailFound' => [
        'subject' => '-- Email deleted --',
        'body' => '-- This email has been deleted. Ethical phishing data are still saved! --',
    ],
    'deleteMessages' => [
        'explanation' => [
            'notDeleted' => 'The selected emails belong to an active campaign and cannot be deleted. Please remove the associated campaign first...',
            'success' => 'All selected emails have been successfully removed.',
            'partial' => 'Some emails were successfully deleted, but the following emails could not be deleted: <br>{emails}<br>Please remove the associated campaign first...',
        ],
        'notDeleted' => 'Emails could not be deleted!',
        'success' => 'Emails deleted successfully!',
        'partial' => 'Some emails were deleted, but others could not be removed!',
    ]

];
