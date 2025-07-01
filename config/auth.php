<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        // Admins (painel web)
        'web' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        // API com usuários comuns usando Passport
        'api' => [
            'driver' => 'passport', // ou 'jwt' se estiver usando JWT
            'provider' => 'users',
            'hash' => false,
        ],

        // Guard para app de usuário logado
        'user' => [
            'driver' => 'sanctum', // ou 'jwt' se preferir
            'provider' => 'users',
        ],

        // Guard para ONGs
        'ong' => [
            'driver' => 'session',
            'provider' => 'ongs',
        ],
    ],

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Adm::class,
        ],

        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // ou App\Models\Usuario se for o caso
        ],

        'ongs' => [
            'driver' => 'eloquent',
            'model' => App\Models\Ong::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
