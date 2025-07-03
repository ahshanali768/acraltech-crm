<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Integration Settings
    |--------------------------------------------------------------------------
    */

    'sync_settings' => [
        'auto_sync' => env('CRM_AUTO_SYNC', true),
        'retry_attempts' => env('CRM_RETRY_ATTEMPTS', 3),
        'retry_delay' => env('CRM_RETRY_DELAY', 60), // seconds
        'timeout' => env('CRM_TIMEOUT', 30), // seconds
        'batch_size' => env('CRM_BATCH_SIZE', 100),
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    */

    'webhooks' => [
        'enabled' => env('CRM_WEBHOOKS_ENABLED', false),
        'secret' => env('CRM_WEBHOOK_SECRET'),
        'endpoints' => [
            'lead_updated' => '/webhooks/crm/lead-updated',
            'lead_deleted' => '/webhooks/crm/lead-deleted',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Telephony Provider Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for telephony providers used for DID management and
    | call tracking. Supports multiple providers like Twilio, Plivo, etc.
    |
    */

    'telephony' => [
        'default_provider' => env('TELEPHONY_DEFAULT_PROVIDER', 'twilio'),
        
        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'webhook_url' => env('TWILIO_WEBHOOK_URL'),
            'rates' => [
                'local' => 0.0085, // per minute
                'toll_free' => 0.0120,
                'setup_fee' => 1.00,
                'monthly_fee' => 1.00,
            ],
        ],
        
        'plivo' => [
            'auth_id' => env('PLIVO_AUTH_ID'),
            'auth_token' => env('PLIVO_AUTH_TOKEN'),
            'webhook_url' => env('PLIVO_WEBHOOK_URL'),
            'rates' => [
                'local' => 0.0070,
                'toll_free' => 0.0100,
                'setup_fee' => 0.80,
                'monthly_fee' => 0.80,
            ],
        ],
        
        'bandwidth' => [
            'user_id' => env('BANDWIDTH_USER_ID'),
            'api_token' => env('BANDWIDTH_API_TOKEN'),
            'api_secret' => env('BANDWIDTH_API_SECRET'),
            'webhook_url' => env('BANDWIDTH_WEBHOOK_URL'),
            'rates' => [
                'local' => 0.0065,
                'toll_free' => 0.0090,
                'setup_fee' => 0.75,
                'monthly_fee' => 0.75,
            ],
        ],
    ],
];
