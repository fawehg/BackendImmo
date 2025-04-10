<?php

return [
    'defaults' => [
        'guard' => 'clients', // Guard par défaut (vous pouvez choisir 'clients' ou 'vendeurs')
        'passwords' => 'clients', // Réinitialisation de mot de passe par défaut
    ],

    'guards' => [
        'clients' => [ // Guard pour les clients
            'driver' => 'jwt',
            'provider' => 'clients',
        ],

        'vendeurs' => [ // Guard pour les vendeurs
            'driver' => 'jwt',
            'provider' => 'vendeurs',
        ],
    ],

    'providers' => [
        'clients' => [ // Provider pour les clients
            'driver' => 'eloquent',
            'model' => App\Models\Client::class,
        ],

        'vendeurs' => [ // Provider pour les vendeurs
            'driver' => 'eloquent',
            'model' => App\Models\Vendeur::class,
        ],
    ],

    'passwords' => [
        'clients' => [ // Réinitialisation de mot de passe pour les clients
            'provider' => 'clients',
            'table' => 'password_reset_tokens_clients',
            'expire' => 60,
            'throttle' => 60,
        ],

        'vendeurs' => [ // Réinitialisation de mot de passe pour les vendeurs
            'provider' => 'vendeurs',
            'table' => 'password_reset_tokens_vendeurs',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];