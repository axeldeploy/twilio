<?php

return [
    'key'               => env('TWILIO_APP_KEY'),
    'secret'            => env('TWILIO_APP_SECRET'),
    'sid'               => env('TWILIO_APP_SID'),
    'from'              => env('TWILIO_APP_FROM'),
    'countries'         => [
        "US",
    ],
    'skip_twilio_check' => false
];