<?php

return [
    'adminEmail' => 'www.artem.matveev@gmail.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Notification about new transaction',

    //Salt for md5 hash
    'salt' => 'DF4500C',

    //Default delay for waiting on the loop
    'delay' => 20,

    //Entry point to wallet api.
    'wallet_api_url' => 'http://' . (getenv('API_HOST', true) ?: getenv('API_HOST')) . '/wallet/default',
];
