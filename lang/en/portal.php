<?php

return [
    
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',



    'status' => [
        'pending' => 'Menunggu Kelulusan',
        'approved' => 'Lulus',
        'rejected' => 'Ditolak',
    ],
    'user' => [
        'status' => [
            'pending' => 'Menunggu Kelulusan',
            'active' => 'Aktif',
            'suspended' => 'Suspended',
        ],
        'category' => [
            'internal' => 'Dalaman',
            'external' => 'Luaran'
        ],
        'model_label' => 'User',
        'plural_model_label' => 'User List',
        'navigation_group' => 'System Management',
        'columns' => [
            'name' => 'Name',
            'email' => 'Email',
            'email_verified_at' => 'Email Verified At',
            'position' => 'Job Position',
            'roles' => 'Role',
            'phone' => 'Phone Number',
            'department' => 'Department',
            'unit' => 'Unit',
            'agency_name' => 'Agency Name',
            'user_category' => 'User Category'
        ],
    ]

];