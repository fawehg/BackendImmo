<?php

return [
  'defaults' => [
    'guard' => 'admins', // ou 'admins' si tu veux que l’admin soit le par défaut
    'passwords' => 'admins',
],

'guards' => [
    'clients' => [
        'driver' => 'jwt',
        'provider' => 'clients',
    ],

    'vendeurs' => [
        'driver' => 'jwt',
        'provider' => 'vendeurs',
    ],

    'admins' => [
        'driver' => 'session', // pour admin login classique
        'provider' => 'admins',
    ],
],

'providers' => [
    'clients' => [
        'driver' => 'eloquent',
        'model' => App\Models\Client::class,
    ],

    'vendeurs' => [
        'driver' => 'eloquent',
        'model' => App\Models\Vendeur::class,
    ],

    'admins' => [
        'driver' => 'eloquent',
        'model' => App\Models\Admin::class,
    ],
],

'passwords' => [
    'clients' => [
        'provider' => 'clients',
        'table' => 'password_reset_tokens_clients',
        'expire' => 60,
        'throttle' => 60,
    ],

    'vendeurs' => [
        'provider' => 'vendeurs',
        'table' => 'password_reset_tokens_vendeurs',
        'expire' => 60,
        'throttle' => 60,
    ],

    'admins' => [
        'provider' => 'admins',
        'table' => 'password_reset_tokens_admins',
        'expire' => 60,
        'throttle' => 60,
    ],
],


    'password_timeout' => 10800,
];