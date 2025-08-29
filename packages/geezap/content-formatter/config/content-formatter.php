<?php

return [
    'deepseek_api_key' => env('DEEPSEEK_API_KEY'),
    'queue_connection' => env('CONTENT_FORMATTER_QUEUE', 'default'),
    'queue_name' => 'content-formatter',
];