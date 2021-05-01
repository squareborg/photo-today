<?php

return [
    'roles' => [
        [
            'name' => 'admin',
            'guard_name' => 'api',
            'permissions' => [

            ],
        ],
        [
            'name' => 'user',
            'guard_name' => 'api',
            'permissions' => [

            ],
        ],
    ],

    'permissions' => [
        [
            'name' => 'add users'
        ],
        [
            'name' => 'edit users'
        ],
        [
            'name' => 'delete users'
        ]
    ],

    'users' => [
        [
            'name' => 'admin',
            'email' => 'sm@sqware.xyz',
            'password' => 'Yellow2020%',
            'role' => 'admin',
        ]
    ],
];
