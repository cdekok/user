<?php
return [
    'routes' => [
        'user-login' => [
            'route' => '/login',
            'params' => [
                'module'     => '\Cept\User',
                'namespace'  => '\Cept\User\Controller',
                'controller' => 'User',
                'action'     => 'login',
            ]
        ]
    ]
];