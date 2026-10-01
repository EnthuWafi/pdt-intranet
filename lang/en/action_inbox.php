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
    ],

    'notifications' => [
        'claimed'  => 'Task has been claimed and assigned to you.',
        'released' => 'Task released back to the departmental pool.',
    ],

    'audit' => [
        'unassigned_pool'         => 'Shared Pool',
        'reassigned_note'         => 'Reassigned to :assignee by :actor. Reason: :reason',
        'transferred_description' => 'Transferred from :previous. Reason: :reason',
    ]
];
