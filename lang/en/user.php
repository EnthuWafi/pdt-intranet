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