<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AllowedLocation;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function index()
    {
        // Redirect to the new unified settings page
        return redirect()->to(route('admin.settings') . '#attendance');
    }

    public function create()
    {
        // Redirect to the new unified settings page
        return redirect()->to(route('admin.settings') . '#attendance');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:1000',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'radius_meters' => 'required|integer|min:50|max:5000',
                'location_type' => 'required|string|in:office,branch,remote,home,coworking',
                'notes' => 'nullable|string|max:1000'
            ]);

            AllowedLocation::create([
                'name' => $request->name,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'radius_meters' => $request->radius_meters,
                'location_type' => $request->location_type,
                'notes' => $request->notes,
                'created_by' => Auth::id()
            ]);

            // Handle AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Allowed location added successfully!'
                ]);
            }

            return redirect()->to(route('admin.settings') . '#attendance')
                ->with('success', 'Allowed location added successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create location: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    public function edit(AllowedLocation $location)
    {
        // Redirect to the new unified settings page
        return redirect()->to(route('admin.settings') . '#attendance');
    }

    public function update(Request $request, AllowedLocation $location)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:1000',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'radius_meters' => 'required|integer|min:50|max:5000',
                'location_type' => 'required|string|in:office,branch,remote,home,coworking',
                'notes' => 'nullable|string|max:1000',
                'is_active' => 'boolean'
            ]);

            $updateData = $request->only([
                'name', 'address', 'latitude', 'longitude', 
                'radius_meters', 'location_type', 'notes', 'is_active'
            ]);

            $location->update($updateData);

            // Handle AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Location updated successfully!'
                ]);
            }

            return redirect()->to(route('admin.settings') . '#attendance')
                ->with('success', 'Location updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update location: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    public function destroy(AllowedLocation $location)
    {
        $location->delete();

        // Handle AJAX requests
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Location deleted successfully!'
            ]);
        }

        return redirect()->to(route('admin.settings') . '#attendance')
            ->with('success', 'Location deleted successfully!');
    }

    public function toggle(AllowedLocation $location)
    {
        $location->update(['is_active' => !$location->is_active]);

        $status = $location->is_active ? 'activated' : 'deactivated';
        return response()->json([
            'success' => true,
            'message' => "Location {$status} successfully!",
            'is_active' => $location->is_active
        ]);
    }

    public function getCurrentLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        // Reverse geocode to get address
        $address = $this->reverseGeocode($request->latitude, $request->longitude);

        return response()->json([
            'success' => true,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $address
        ]);
    }

    public function validateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $result = AllowedLocation::isLocationAllowed($request->latitude, $request->longitude);

        return response()->json($result);
    }

    private function reverseGeocode($latitude, $longitude)
    {
        // This is a simple implementation. In production, you might want to use
        // Google Maps Geocoding API, OpenStreetMap Nominatim, or similar service
        return "Lat: {$latitude}, Lng: {$longitude}, Kolkata, West Bengal, India";
    }

    public function getLocationSettings()
    {
        $locations = AllowedLocation::getActiveLocations();
        
        return response()->json([
            'locations' => $locations->map(function ($location) {
                return [
                    'id' => $location->id,
                    'name' => $location->name,
                    'address' => $location->address,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'radius_meters' => $location->radius_meters,
                    'location_type' => $location->location_type
                ];
            })
        ]);
    }

    public function show(AllowedLocation $location)
    {
        return response()->json([
            'success' => true,
            'location' => $location
        ]);
    }
}
