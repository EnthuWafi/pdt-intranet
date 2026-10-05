<?php

return [
    'resource' => [
        'label'        => 'Module',
        'plural_label' => 'Modules',
    ],

    'my' => [
        'title' => 'My Modules'
    ],

    'requests' => [
        'resource' => [
            'label'        => 'Module Request',
            'plural_label' => 'Module Requests',
        ],
        'choose_role'     => 'Choose Role',
        'list_available'  => 'List of Available Modules',
        'purpose_request' => 'Purpose of Request',
    ],

    'columns' => [
        'name'          => 'Module Name',
        'status'        => 'Module Status',
        'role_assigned' => 'Assigned Role',
        'purpose'       => 'Purpose',
        'created_at'    => 'Date Requested',
    ],

    'messages' => [
        'request_submitted'   => 'Your access request has been submitted successfully.',
        'access_approved'     => 'Access has been approved.',
        'access_rejected'     => 'Access request has been rejected.',
        'already_applied'     => 'You have already applied for this module. Please wait for approval.',
        'module_request_exist'=> 'A request for this module already exists.',
        'confirm_approve'     => 'Are you sure you want to approve this access request?',
        
    ],
    'actions' => [
        'title_request' => 'Access Request: ',
        'desc_request' => 'Applicant: :applicantname. Justification: :justification.'
    ]
];