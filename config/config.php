<?php
return [
    'routes' => [
        "/login" => [
            'module'     => '\Cept\User',
            'namespace'  => '\Cept\User\Controller',
            'controller' => 'User',
            'action'     => 'login',
        ]
    ]
];