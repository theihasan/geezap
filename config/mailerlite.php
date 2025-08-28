<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MailerLite API Key
    |--------------------------------------------------------------------------
    |
    | Your MailerLite API key. You can find this in your MailerLite account
    | under Account > Integrations > API.
    |
    */
    'key' => env('MAILERLITE_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | MailerLite API URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the MailerLite API. You should not need to change this.
    |
    */
    'url' => env('MAILERLITE_API_URL', 'https://connect.mailerlite.com/api/'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout for API requests in seconds.
    |
    */
    'timeout' => env('MAILERLITE_TIMEOUT', 30),
];
