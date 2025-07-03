<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationSetting;
use App\Models\NotificationTemplate;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        $events = [
            [
                'trigger_event' => 'user_registration',
                'description' => 'New user registers on the system',
                'email_enabled' => true,
                'in_app_enabled' => true,
                'recipients' => ['admins'],
                'frequency' => 'immediate',
                'templates' => [
                    'email' => [
                        'subject' => 'New User Registration - {{user_name}}',
                        'content' => 'A new user {{user_name}} ({{user_email}}) has registered on the system at {{registration_date}}.'
                    ],
                    'in_app' => [
                        'content' => 'New user {{user_name}} registered'
                    ]
                ]
            ],
            [
                'trigger_event' => 'lead_created',
                'description' => 'New lead is added to the system',
                'email_enabled' => true,
                'in_app_enabled' => true,
                'recipients' => ['all'],
                'frequency' => 'immediate',
                'templates' => [
                    'email' => [
                        'subject' => 'New Lead Created - {{lead_name}}',
                        'content' => 'A new lead {{lead_name}} from {{lead_company}} has been created. Contact: {{lead_email}} | {{lead_phone}}'
                    ],
                    'in_app' => [
                        'content' => 'New lead: {{lead_name}} from {{lead_company}}'
                    ]
                ]
            ],
            [
                'trigger_event' => 'password_reset',
                'description' => 'User requests password reset',
                'email_enabled' => true,
                'recipients' => ['user'],
                'frequency' => 'immediate',
                'templates' => [
                    'email' => [
                        'subject' => 'Password Reset Request',
                        'content' => 'You have requested a password reset. Click the link to reset: {{reset_link}}'
                    ]
                ]
            ],
            [
                'trigger_event' => 'payment_received',
                'description' => 'Payment has been received',
                'email_enabled' => true,
                'in_app_enabled' => true,
                'recipients' => ['admins'],
                'frequency' => 'immediate',
                'templates' => [
                    'email' => [
                        'subject' => 'Payment Received - ${{amount}}',
                        'content' => 'Payment of ${{amount}} has been received from {{customer_name}} for {{service_type}}.'
                    ],
                    'in_app' => [
                        'content' => 'Payment received: ${{amount}} from {{customer_name}}'
                    ]
                ]
            ],
            [
                'trigger_event' => 'system_maintenance',
                'description' => 'System maintenance notifications',
                'email_enabled' => true,
                'in_app_enabled' => true,
                'recipients' => ['all'],
                'frequency' => 'immediate',
                'templates' => [
                    'email' => [
                        'subject' => 'System Maintenance Scheduled',
                        'content' => 'System maintenance is scheduled for {{maintenance_date}}. Expected downtime: {{duration}}.'
                    ],
                    'in_app' => [
                        'content' => 'Maintenance scheduled for {{maintenance_date}}'
                    ]
                ]
            ],
            [
                'trigger_event' => 'daily_summary',
                'description' => 'Daily activity summary',
                'email_enabled' => true,
                'recipients' => ['admins'],
                'frequency' => 'daily',
                'templates' => [
                    'email' => [
                        'subject' => 'Daily Summary - {{date}}',
                        'content' => 'Daily Summary for {{date}}: {{new_leads}} new leads, {{total_calls}} calls made, {{revenue}} revenue generated.'
                    ]
                ]
            ]
        ];

        foreach ($events as $eventData) {
            // Create notification setting
            $setting = NotificationSetting::updateOrCreate(
                ['trigger_event' => $eventData['trigger_event']],
                [
                    'description' => $eventData['description'],
                    'email_enabled' => $eventData['email_enabled'] ?? false,
                    'sms_enabled' => $eventData['sms_enabled'] ?? false,
                    'push_enabled' => $eventData['push_enabled'] ?? false,
                    'in_app_enabled' => $eventData['in_app_enabled'] ?? false,
                    'recipients' => $eventData['recipients'],
                    'frequency' => $eventData['frequency'],
                    'is_active' => true
                ]
            );

            // Create templates
            if (isset($eventData['templates'])) {
                foreach ($eventData['templates'] as $channel => $template) {
                    NotificationTemplate::updateOrCreate(
                        [
                            'trigger_event' => $eventData['trigger_event'],
                            'channel' => $channel
                        ],
                        [
                            'subject' => $template['subject'] ?? null,
                            'content' => $template['content'],
                            'variables' => $this->extractVariables($template['content'] . ' ' . ($template['subject'] ?? '')),
                            'is_active' => true
                        ]
                    );
                }
            }
        }
    }

    private function extractVariables($text)
    {
        preg_match_all('/\{\{(\w+)\}\}/', $text, $matches);
        return array_unique($matches[1]);
    }
}
