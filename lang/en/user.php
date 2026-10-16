<?php

return [
    'resource' => [
        'label'        => 'User',
        'plural_label' => 'Users',
    ],

    'status' => [
        'pending'   => 'Pending Approval',
        'active'    => 'Active',
        'suspended' => 'Suspended',
    ],

    'category' => [
        'internal' => 'Internal',
        'external' => 'External',
    ],

    'sections' => [
        'personal_info' => 'User Information',
        'organization_role' => 'Organization & Portal Roles',
        'organization_hint' => 'Internal categories use Department and Unit structures from the Portal Master Data.',
        'external_modules' => 'External Module Access',
        'external_modules_desc' => 'Determine access and roles for each external module.',
    ],

    'fields' => [
        'name'              => 'Full Name',
        'email'             => 'Email / Login ID',
        'email_verified_at' => 'Email Verified At',
        'password'          => 'Password',
        'position'          => 'Job Position',
        'position_id'       => 'Position & Grade',
        'custom_position'   => 'Custom Position (If not in list)',
        'use_custom_position' => 'Position not in list (Enter manually)',
        'roles'             => 'Portal Roles (Internal)',
        'phone'             => 'Phone Number (HP)',
        'department'        => 'Department',
        'unit'              => 'Unit',
        'organization'      => 'Organization / Agency',
        'agency_name'       => 'Agency Name',
        'user_category'     => 'User Category',
        'status'            => 'Registration Status',
        'user_profile'      => 'User Profile',
        'category'          => 'User Category',
        'module_requests'   => 'Module Access List',
        'module'            => 'Module',
        'external_role'     => 'Module Role',
        'request_status'    => 'Approval Status',
    ],

    'placeholders' => [
        'select_or_custom_position' => 'Select a position or mark custom position...',
        'custom_position' => 'Enter job position name...',
    ],

    'actions' => [
        'add_module_access' => 'Add Module Access',
    ],

    'columns' => [
        'name'              => 'Name',
        'email'             => 'Email',
        'email_verified_at' => 'Email Verified At',
        'password'          => 'Password',
        'position'          => 'Job Position',
        'position_id'       => 'Position & Grade',
        'roles'             => 'Role',
        'phone'             => 'Phone Number',
        'department'        => 'Department',
        'unit'              => 'Unit',
        'agency_name'       => 'Agency Name',
        'user_category'     => 'User Category',
        'status'            => 'Status',
        'user_profile'      => 'User Profile',
    ],

    'notification' => [
        'does_not_exist' => 'Selected user does not exist.'
    ],

    'header' => [
        'description' => 'Manage portal staff, departmental assignments, and role access.',
    ],

    'tabs' => [
        'all' => 'All Users',
        'pending' => 'Pending Approval',
        'active' => 'Active',
        'suspended' => 'Suspended',
    ],
];