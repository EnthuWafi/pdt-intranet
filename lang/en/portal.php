<?php

return [
    
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',

    'status' => [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ],

    'actions' => [
        'submit' => 'Submit',
        'apply' => 'Apply for Access',
        'approve' => 'Approve',
        'reject' => 'Reject',
        'cancel' => 'Cancel',
    ],

    'messages' => [
        // Success toasts
        'request_submitted' => 'Your access request has been submitted successfully.',
        'access_approved' => 'Access has been approved.',
        'access_rejected' => 'Access request has been rejected.',
        
        // Warnings & Errors
        'already_applied' => 'You have already applied for this module. Please wait for approval.',
        'action_failed' => 'An error occurred. Please try again.',
        
        // Confirmation Modals
        'confirm_approve' => 'Are you sure you want to approve this access?',
    ],

    //resources

    'user' => [
        'status' => [
            'pending' => 'Pending Approval',
            'active' => 'Active',
            'suspended' => 'Digantung',
        ],
        'category' => [
            'internal' => 'Internal',
            'external' => 'External',
        ],
        'model_label' => 'User',
        'plural_model_label' => 'User List',
        'navigation_group' => 'System Management',
        'columns' => [
            'name' => 'Name',
            'email' => 'Email',
            'email_verified_at' => 'Email Verified At',
            'password' => 'Password',
            'position' => 'Job Position',
            'roles' => 'Role',
            'phone' => 'Phone Number',
            'department' => 'Department',
            'unit' => 'Unit',
            'agency_name' => 'Agency Name',
            'user_category' => 'User Category',
            'status' => 'Status'
        ],
    ],
    'module' => [
        'list_available' => 'List of Available Modules',
        'purpose_request' => 'Purpose of Request',
        'columns' => [
            'status' => 'Module Status',
            'created_at' => 'Date Requested',
            'purpose' => 'Purpose',
            'name' => 'Module Name',
        ],
    ],


];