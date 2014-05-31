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
    ],
    'factories' => [
        '\Cept\User\Model\UserRepo' => '\Cept\User\Model\UserRepoFactory',
        '\Cept\User\Model\RoleRepo' => '\Cept\User\Model\RoleRepoFactory'
    ]
];