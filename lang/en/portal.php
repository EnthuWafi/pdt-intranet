<?php

return [

    'organization_name' => 'Manjung District and Land',
    'organization_shortform' => 'PDT Manjung',
    'portal_name' => 'Intranet Portal',

    'help' => 'Help',
    'faq' => "FAQ",
    'contact' => 'Contact',

    'unknown' => 'Unknown',

    'online' => 'Online',
    'offline' => 'Offline',

    'hello' => 'Hello',
    
    'version' => 'Version',
    
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

    'activity_event' => [
        'created'   => 'Created',
        'updated'   => 'Updated',
        'deleted'   => 'Deleted',
        'requested' => 'Requested',
        'approved'  => 'Approved',
        'rejected'  => 'Rejected',
    ],

    'messages' => [
        // Success toasts
        'request_submitted' => 'Your access request has been submitted successfully.',
        'access_approved' => 'Access has been approved.',
        'access_rejected' => 'Access request has been rejected.',
        
        // Warnings & Errors
        'already_applied' => 'You have already applied for this. Please wait for approval.',
        'action_failed' => 'An error occurred. Please try again.',
        'module_request_exist' => 'A request with this module already exists.',
        // Confirmation Modals
        'confirm_approve' => 'Are you sure you want to approve this access?',
    ],

    

    'roles' => [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'user' => 'User',
        'viewer' => 'Viewer'
    ],

    //resources

    'user' => [
        'status' => [
            'pending' => 'Pending Approval',
            'active' => 'Active',
            'suspended' => 'Suspended',
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
            'status' => 'Status',
            'user_profile' => 'User Profile',
            
        ],
    ],
    'module' => [
        'choose_role' => 'Choose Role',
        'list_available' => 'List of Available Modules',
        'purpose_request' => 'Purpose of Request',
        'columns' => [
            'status' => 'Module Status',
            'created_at' => 'Date Requested',
            'purpose' => 'Purpose',
            'name' => 'Module Name',
            'role_assigned' => "Assigned Role",
        ],
    ],

    'announcement' => [
        'type' => [
            'info' => 'Infomation',
            'success' => 'Success',
            'danger' => 'Danger',
            'warning' => 'Warning',
        ],
        'columns' => [
            'starts_at' => 'Starts At',
            'expires_at' => 'Expires At',
            'type' => 'Type',
            'title' => 'Title',
            'content' => 'Content',
            'is_active' => 'Is Active',

        ]
    ]


];