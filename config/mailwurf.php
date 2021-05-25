<?php

return [
    'main' => [
        'domain' => env('MAILWURF_DOMAIN', 'mailwurf.de'),
    ],
    'mailboxes' => [
        'default',
    ],
    'keep_mails' => [
        'default' => 7,
        'minimum' => 1,
        'maximum' => 30,
    ],
];
