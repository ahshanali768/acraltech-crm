<?php

namespace App\Services;

class LocationService
{
    /**
     * Calculate distance between two coordinates using Haversine formula
     * Returns distance in meters
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Earth radius in meters
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c; // Distance in meters
    }
    
    /**
     * Check if coordinates are within allowed distance from office
     */
    public static function isWithinOfficeRadius($latitude, $longitude)
    {
        $officeConfig = config('attendance');
        $distance = self::calculateDistance(
            $latitude,
            $longitude,
            $officeConfig['office_latitude'],
            $officeConfig['office_longitude']
        );
        
        return [
            'within_radius' => $distance <= $officeConfig['max_distance_meters'],
            'distance_meters' => round($distance),
            'max_allowed_meters' => $officeConfig['max_distance_meters']
        ];
    }
    
    /**
     * Reverse geocode coordinates to get address
     * This is a placeholder - in production you'd use a real geocoding service
     */
    public static function reverseGeocode($latitude, $longitude)
    {
        // For now, return a generic address format
        // In production, you could use Google Maps API, OpenStreetMap, etc.
        return "Lat: {$latitude}, Lng: {$longitude}, Kolkata, West Bengal, India";
    }
    
    /**
     * Check if IP address is from allowed office ranges
     */
    public static function isOfficeIP($ipAddress)
    {
        $allowedRanges = config('attendance.allowed_ip_ranges', []);
        
        if (empty($allowedRanges)) {
            return true; // No IP restrictions configured
        }
        
        foreach ($allowedRanges as $range) {
            if (self::ipInRange($ipAddress, $range)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if IP is in CIDR range
     */
    private static function ipInRange($ip, $range)
    {
        if (strpos($range, '/') === false) {
            return $ip === $range;
        }
        
        list($subnet, $bits) = explode('/', $range);
        
        if ($bits === null) {
            $bits = 32;
        }
        
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask;
        
        return ($ip & $mask) === $subnet;
    }
    
    /**
     * Get user's IP address considering proxies
     */
    public static function getUserIP()
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',     // CloudFlare
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
    
    /**
     * Validate location data structure
     */
    public static function validateLocationData($locationData)
    {
        $required = ['latitude', 'longitude'];
        
        foreach ($required as $field) {
            if (!isset($locationData[$field]) || !is_numeric($locationData[$field])) {
                return false;
            }
        }
        
        // Check latitude range
        if ($locationData['latitude'] < -90 || $locationData['latitude'] > 90) {
            return false;
        }
        
        // Check longitude range
        if ($locationData['longitude'] < -180 || $locationData['longitude'] > 180) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if coordinates are within any allowed location radius
     */
    public static function isWithinAllowedLocation($latitude, $longitude)
    {
        $allowedLocations = \App\Models\AllowedLocation::where('is_active', true)->get();
        
        $result = [
            'allowed' => false,
            'distance_meters' => null,
            'closest_location' => null,
            'within_locations' => []
        ];
        
        $minDistance = PHP_FLOAT_MAX;
        $closestLocation = null;
        
        foreach ($allowedLocations as $location) {
            $distance = self::calculateDistance(
                $latitude,
                $longitude,
                $location->latitude,
                $location->longitude
            );
            
            // Track closest location
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $closestLocation = $location;
            }
            
            // Check if within this location's radius
            if ($distance <= $location->radius_meters) {
                $result['allowed'] = true;
                $result['within_locations'][] = [
                    'id' => $location->id,
                    'name' => $location->name,
                    'distance_meters' => round($distance),
                    'radius_meters' => $location->radius_meters
                ];
            }
        }
        
        $result['distance_meters'] = round($minDistance);
        $result['closest_location'] = $closestLocation ? [
            'id' => $closestLocation->id,
            'name' => $closestLocation->name,
            'distance_meters' => round($minDistance),
            'radius_meters' => $closestLocation->radius_meters
        ] : null;
        
        return $result;
    }
}
