<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AllowedLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllowedLocationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'location_name' => 'required|string|max:255',
            'location_type' => 'required|in:address,ip,geo',
            'location_value' => 'required|string|max:500',
            'radius_meters' => 'nullable|numeric|min:10|max:1000',
        ]);

        $data = [
            'name' => $request->location_name,
            'location_type' => $request->location_type,
            'is_active' => true,
            'created_by' => Auth::id(),
        ];

        // Handle different location types
        switch ($request->location_type) {
            case 'geo':
                // Parse GPS coordinates from "latitude,longitude" format
                $coords = explode(',', $request->location_value);
                if (count($coords) !== 2) {
                    return back()->withErrors(['location_value' => 'GPS coordinates must be in format: latitude,longitude']);
                }
                
                $latitude = trim($coords[0]);
                $longitude = trim($coords[1]);
                
                if (!is_numeric($latitude) || !is_numeric($longitude)) {
                    return back()->withErrors(['location_value' => 'GPS coordinates must be valid numbers']);
                }
                
                if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
                    return back()->withErrors(['location_value' => 'GPS coordinates are out of valid range']);
                }
                
                $data['latitude'] = $latitude;
                $data['longitude'] = $longitude;
                $data['radius_meters'] = $request->radius_meters ?? 100; // Use provided radius or default
                $data['address'] = $request->address ?? $request->location_value; // Use provided address or coordinates
                break;
                
            case 'address':
                $data['address'] = $request->location_value;
                break;
                
            case 'ip':
                $data['address'] = $request->location_value;
                // Validate IP address format
                if (!filter_var($request->location_value, FILTER_VALIDATE_IP)) {
                    return back()->withErrors(['location_value' => 'Please enter a valid IP address']);
                }
                break;
        }

        AllowedLocation::create($data);

        return redirect()->back()
                        ->with('success', 'Location "' . $request->location_name . '" added successfully');
    }

    public function toggle($id)
    {
        $location = AllowedLocation::findOrFail($id);
        $location->is_active = !$location->is_active;
        $location->save();

        $status = $location->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.settings')
                        ->with('success', 'Location "' . $location->name . '" has been ' . $status);
    }

    public function destroy($id)
    {
        $location = AllowedLocation::findOrFail($id);
        $location->delete();
        return redirect()->route('admin.settings')->with('success', 'Location deleted successfully');
    }
}
