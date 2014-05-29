<?php
return [
    'routes' => [
        'user-login' => [
            'route' => '/login',
            'params' => [
                'module'     => 'cept-user',
                'namespace'  => '\Cept\User\Controller',
                'controller' => 'User',
                'action'     => 'login',
            ]
        ]
    ],
    'commands' => [
        '\Cept\User\Cli\UserAdd'
    ]
];