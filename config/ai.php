<?php

return [
    'chat_gpt_api_key' => env('OPEN_AI_API_KEY'),
    'gemini_api_key' => env('GEMINI_API_KEY'),
    'search_api' => [
        'base_url' => env('SEARCH_API_BASE_URL'),
        'api_key' => env('SEARCH_API_KEY'),
    ]
];
