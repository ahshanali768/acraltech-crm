<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Acraltech CRM', 'type' => 'string'],
            ['key' => 'site_description', 'value' => 'Professional CRM Solution', 'type' => 'string'],
            ['key' => 'site_url', 'value' => config('app.url'), 'type' => 'string'],
            ['key' => 'admin_email', 'value' => 'admin@acraltech.site', 'type' => 'string'],
            ['key' => 'timezone', 'value' => 'UTC', 'type' => 'string'],
            ['key' => 'language', 'value' => 'en', 'type' => 'string'],
            ['key' => 'currency', 'value' => 'USD', 'type' => 'string'],
            ['key' => 'date_format', 'value' => 'm/d/Y', 'type' => 'string'],
            ['key' => 'smtp_host', 'value' => 'smtp.hostinger.com', 'type' => 'string'],
            ['key' => 'smtp_port', 'value' => '465', 'type' => 'integer'],
            ['key' => 'smtp_username', 'value' => 'admin@acraltech.site', 'type' => 'string'],
            ['key' => 'smtp_password', 'value' => 'Health@768', 'type' => 'string'],
            ['key' => 'smtp_encryption', 'value' => 'ssl', 'type' => 'string'],
            ['key' => 'mail_from_name', 'value' => 'Acraltech CRM', 'type' => 'string'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean'],
            ['key' => 'maintenance_message', 'value' => 'We\'re performing scheduled maintenance. Please check back soon.', 'type' => 'string'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }
    }
}
