<?php 

return [
    'title' => 'Action Inbox',
    'empty' => 'No pending actions require your attention.',

    'fields' => [
        'title'       => 'Action',
        'stage'       => 'Workflow Stage',
        'assigned_to' => 'Assigned Officer',
        'received_at' => 'Received',
    ],

    'status' => [
        'unassigned' => 'Unassigned (Pool)',
    ],

    'actions' => [
        'open'    => 'Open',
        'claim'   => 'Claim Task',
        'release' => 'Release Task',
        'spawn'   => 'Request Action / Delegate',
    ],

    'spawn' => [
        'title' => 'Request Action / Delegate',
        'fields' => [
            'title' => 'Task Summary',
            'title_placeholder' => 'e.g., Verify quota allocation, Check server logs',
            'assignment_type' => 'Assignment Target',
            'pool' => 'Shared Department Pool (Anyone can pick up)',
            'subordinate' => 'Assign to Specific Subordinate',
            'assigned_to_user_id' => 'Select Subordinate',
            'instructions' => 'Instructions / Notes',
            'instructions_placeholder' => 'Describe what needs to be verified or updated...',
            'required_permissions' => 'Required Permissions',
        ],
    ],

    'notifications' => [
        'claimed'  => 'Task has been claimed and assigned to you.',
        'released' => 'Task released back to the departmental pool.',
        'assigned' => 'Task assigned to :name',
        'unassigned' =>  'Task sent to shared department pool.',
        'delegated_title' => 'Task Delegated',
        'delegated_body' => ":assignedByName assigned ':taskTitle' to you.",
    ],

    'audit' => [
        'unassigned_pool'         => 'Shared Pool',
        'reassigned_note'         => 'Reassigned to :assignee by :actor. Reason: :reason',
        'transferred_description' => 'Transferred from :previous. Reason: :reason',
    ]
];
