<?php

return [
    'globalWebsite' => [
        'url' => env('MY_SERVER_URL','http://bookingsys/'),
        'defaultCountryId' => 578,
        'baseUrl' => 'http://bookingsys/',
        'system_email' => 'booking_agent@book247.net',
        'auto_show_status_change' => false
        // etc
    ],
    'finance' => [
        'currency' => 'NOK'
    ]
];