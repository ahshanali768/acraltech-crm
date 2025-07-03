<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllowedLocation extends Model
{
    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'radius_meters',
        'is_active',
        'location_type',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'radius_meters' => 'integer',
        'is_active' => 'boolean'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if coordinates are within this location's radius
     */
    public function isWithinRadius($latitude, $longitude)
    {
        $distance = $this->calculateDistance($latitude, $longitude);
        return $distance <= $this->radius_meters;
    }

    /**
     * Calculate distance from this location to given coordinates
     */
    public function calculateDistance($latitude, $longitude)
    {
        $earthRadius = 6371000; // Earth radius in meters
        
        $dLat = deg2rad($latitude - $this->latitude);
        $dLon = deg2rad($longitude - $this->longitude);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($this->latitude)) * cos(deg2rad($latitude)) *
             sin($dLon/2) * sin($dLon/2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }

    /**
     * Get all active allowed locations
     */
    public static function getActiveLocations()
    {
        return self::where('is_active', true)->get();
    }

    /**
     * Check if coordinates are within any allowed location
     */
    public static function isLocationAllowed($latitude, $longitude)
    {
        $locations = self::getActiveLocations();
        
        foreach ($locations as $location) {
            if ($location->isWithinRadius($latitude, $longitude)) {
                return [
                    'allowed' => true,
                    'location' => $location,
                    'distance' => $location->calculateDistance($latitude, $longitude)
                ];
            }
        }

        // Find the closest location for feedback
        $closestLocation = null;
        $shortestDistance = PHP_FLOAT_MAX;

        foreach ($locations as $location) {
            $distance = $location->calculateDistance($latitude, $longitude);
            if ($distance < $shortestDistance) {
                $shortestDistance = $distance;
                $closestLocation = $location;
            }
        }

        return [
            'allowed' => false,
            'closest_location' => $closestLocation,
            'closest_distance' => $shortestDistance
        ];
    }
}
