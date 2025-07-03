<?php

namespace App\Services;

use Illuminate\Support\Str;
use Carbon\Carbon;

class LeadTrackingService
{
    /**
     * Generate realistic tracking data based on lead location
     */
    public static function generateTrackingData($leadData)
    {
        $state = $leadData['state'] ?? 'CA';
        $city = $leadData['city'] ?? 'Los Angeles';
        $zip = $leadData['zip'] ?? '90210';
        
        return [
            'ip_address' => self::generateUSAIPAddress($state),
            'user_agent' => self::generateUserAgent(),
            'jornaya_lead_id' => self::generateJornayaLeadId(),
            'trusted_form_cert_id' => self::generateTrustedFormCertId(),
            'trusted_form_url' => self::generateTrustedFormUrl(),
            'device_fingerprint' => self::generateDeviceFingerprint(),
            'browser_language' => 'en-US',
            'timezone' => self::getTimezoneByState($state),
            'cookies_data' => self::generateCookiesData(),
            'referrer_url' => self::generateReferrerUrl(),
            'landing_page_url' => self::generateLandingPageUrl(),
            'lead_submitted_at' => self::generateSubmissionTime(),
            'session_id' => self::generateSessionId()
        ];
    }

    /**
     * Generate USA IP address based on state
     */
    private static function generateUSAIPAddress($state)
    {
        // IP ranges for different US states/regions
        $stateIPRanges = [
            'CA' => ['173.252.', '104.244.', '192.41.', '199.59.'],
            'NY' => ['162.158.', '173.252.', '104.16.', '192.0.'],
            'TX' => ['198.143.', '162.255.', '104.244.', '173.252.'],
            'FL' => ['192.41.', '199.59.', '162.158.', '104.16.'],
            'IL' => ['173.252.', '104.244.', '198.143.', '162.255.'],
            'PA' => ['192.0.', '173.252.', '104.16.', '162.158.'],
            'OH' => ['104.244.', '198.143.', '173.252.', '162.255.'],
            'default' => ['173.252.', '104.244.', '192.41.', '199.59.']
        ];

        $prefixes = $stateIPRanges[$state] ?? $stateIPRanges['default'];
        $prefix = $prefixes[array_rand($prefixes)];
        
        $third = rand(1, 254);
        $fourth = rand(1, 254);
        
        return $prefix . $third . '.' . $fourth;
    }

    /**
     * Generate realistic user agent string
     */
    private static function generateUserAgent()
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36 Edg/118.0.2088.76',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.1 Safari/605.1.15',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/119.0',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36'
        ];

        return $userAgents[array_rand($userAgents)];
    }

    /**
     * Generate Jornaya Lead ID (format: typical Jornaya token)
     */
    private static function generateJornayaLeadId()
    {
        return strtoupper(Str::random(8) . '-' . Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4) . '-' . Str::random(12));
    }

    /**
     * Generate TrustedForm Certificate ID
     */
    private static function generateTrustedFormCertId()
    {
        return strtolower(Str::random(8) . '-' . Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4) . '-' . Str::random(12));
    }

    /**
     * Generate TrustedForm URL
     */
    private static function generateTrustedFormUrl()
    {
        $certId = self::generateTrustedFormCertId();
        return "https://cert.trustedform.com/{$certId}";
    }

    /**
     * Generate device fingerprint
     */
    private static function generateDeviceFingerprint()
    {
        return md5(Str::random(40) . time());
    }

    /**
     * Get timezone based on state
     */
    private static function getTimezoneByState($state)
    {
        $timezones = [
            'CA' => 'America/Los_Angeles',
            'NY' => 'America/New_York',
            'TX' => 'America/Chicago',
            'FL' => 'America/New_York',
            'IL' => 'America/Chicago',
            'PA' => 'America/New_York',
            'OH' => 'America/New_York',
            'WA' => 'America/Los_Angeles',
            'OR' => 'America/Los_Angeles',
            'NV' => 'America/Los_Angeles',
            'AZ' => 'America/Phoenix',
            'MT' => 'America/Denver',
            'CO' => 'America/Denver',
            'NM' => 'America/Denver',
            'UT' => 'America/Denver',
            'WY' => 'America/Denver',
            'ND' => 'America/Chicago',
            'SD' => 'America/Chicago',
            'NE' => 'America/Chicago',
            'KS' => 'America/Chicago',
            'OK' => 'America/Chicago',
            'MN' => 'America/Chicago',
            'IA' => 'America/Chicago',
            'MO' => 'America/Chicago',
            'AR' => 'America/Chicago',
            'LA' => 'America/Chicago',
            'WI' => 'America/Chicago',
            'IN' => 'America/New_York',
            'MI' => 'America/New_York',
            'KY' => 'America/New_York',
            'TN' => 'America/Chicago',
            'MS' => 'America/Chicago',
            'AL' => 'America/Chicago',
            'GA' => 'America/New_York',
            'SC' => 'America/New_York',
            'NC' => 'America/New_York',
            'VA' => 'America/New_York',
            'WV' => 'America/New_York',
            'MD' => 'America/New_York',
            'DE' => 'America/New_York',
            'NJ' => 'America/New_York',
            'CT' => 'America/New_York',
            'RI' => 'America/New_York',
            'MA' => 'America/New_York',
            'VT' => 'America/New_York',
            'NH' => 'America/New_York',
            'ME' => 'America/New_York',
            'default' => 'America/New_York'
        ];

        return $timezones[$state] ?? $timezones['default'];
    }

    /**
     * Generate realistic cookies data
     */
    private static function generateCookiesData()
    {
        return json_encode([
            '_ga' => 'GA1.1.' . rand(100000000, 999999999) . '.' . time(),
            '_gid' => 'GA1.1.' . rand(100000000, 999999999),
            '_fbp' => 'fb.1.' . time() . '.' . rand(100000000, 999999999),
            'session_token' => Str::random(32),
            'visitor_id' => Str::random(16),
            'utm_source' => self::getRandomUtmSource(),
            'utm_medium' => 'cpc',
            'utm_campaign' => self::getRandomCampaignName(),
            'landing_timestamp' => time() - rand(300, 3600) // 5 minutes to 1 hour ago
        ]);
    }

    /**
     * Generate random UTM source
     */
    private static function getRandomUtmSource()
    {
        $sources = ['google', 'facebook', 'bing', 'yahoo', 'duckduckgo', 'direct', 'email'];
        return $sources[array_rand($sources)];
    }

    /**
     * Generate random campaign name
     */
    private static function getRandomCampaignName()
    {
        $campaigns = [
            'insurance_quotes_2024',
            'auto_insurance_leads',
            'health_insurance_compare',
            'life_insurance_quotes',
            'home_insurance_rates',
            'business_insurance_leads',
            'cheap_car_insurance',
            'senior_health_plans'
        ];
        return $campaigns[array_rand($campaigns)];
    }

    /**
     * Generate referrer URL
     */
    private static function generateReferrerUrl()
    {
        $referrers = [
            'https://www.google.com/search?q=cheap+car+insurance',
            'https://www.facebook.com/ads/insurance',
            'https://www.bing.com/search?q=auto+insurance+quotes',
            'https://compare-insurance.com/quotes',
            'https://insurancequotes.org/compare',
            'https://www.yahoo.com/search?p=insurance+rates',
            'https://duckduckgo.com/?q=insurance+comparison'
        ];
        return $referrers[array_rand($referrers)];
    }

    /**
     * Generate landing page URL
     */
    private static function generateLandingPageUrl()
    {
        $domains = [
            'insurancequotes.com',
            'getinsurancenow.com',
            'comparerates.org',
            'cheapinsurance.net',
            'quotestoday.com'
        ];
        $domain = $domains[array_rand($domains)];
        
        $pages = [
            '/auto-insurance',
            '/health-insurance', 
            '/home-insurance',
            '/life-insurance',
            '/get-quote',
            '/compare-rates'
        ];
        $page = $pages[array_rand($pages)];
        
        return "https://www.{$domain}{$page}?utm_source=" . self::getRandomUtmSource();
    }

    /**
     * Generate realistic submission time (within last few hours)
     */
    private static function generateSubmissionTime()
    {
        $minutesAgo = rand(5, 240); // 5 minutes to 4 hours ago
        return Carbon::now()->subMinutes($minutesAgo)->format('Y-m-d H:i:s');
    }

    /**
     * Generate session ID
     */
    private static function generateSessionId()
    {
        return 'sess_' . Str::random(32);
    }
}
