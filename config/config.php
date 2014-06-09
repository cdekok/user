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
        ],
        'user-profile' => [
            'route' => '/profile',
            'params' => [
                'module'     => 'cept-user',
                'namespace'  => '\Cept\User\Controller',
                'controller' => 'User',
                'action'     => 'profile',
            ]
        ],
        'user-logout' => [
            'route' => '/logout',
            'params' => [
                'module'     => 'cept-user',
                'namespace'  => '\Cept\User\Controller',
                'controller' => 'User',
                'action'     => 'logout',
            ]
        ],
    ],
    'commands' => [
        '\Cept\User\Cli\UserAdd'
    ],
    'factories' => [
        '\Cept\User\Model\UserRepo' => '\Cept\User\Model\UserRepoFactory',
        '\Cept\User\Model\RoleRepo' => '\Cept\User\Model\RoleRepoFactory',
        '\Cept\User\Model\PermissionRepo' => '\Cept\User\Model\PermissionRepoFactory',
        '\Cept\User\Auth\Auth' => '\Cept\User\Auth\AuthFactory',
        '\Cept\User\Auth\Identity' => '\Cept\User\Auth\IdentityFactory',
        '\Cept\User\Acl\Acl' => '\Cept\User\Acl\AclFactory',
    ]
];