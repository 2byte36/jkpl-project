<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index()
    {
        $venues = Venue::with('city')->get();

        return response()->json([
            'status' => 'success',
            'data' => $venues
        ]);
    }

    public function show($id)
    {
        $venue = Venue::with('city')->find($id);

        if (!$venue) {
            return response()->json([
                'status' => 'error',
                'message' => 'Venue not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $venue
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $venue = Venue::create([
            'name' => $request->name,
            'city_id' => $request->city_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $venue->load('city')
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $venue = Venue::find($id);

        if (!$venue) {
            return response()->json([
                'status' => 'error',
                'message' => 'Venue not found'
            ], 404);
        }

        $this->validate($request, [
            'name' => 'sometimes|string',
            'city_id' => 'sometimes|exists:cities,id',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180'
        ]);

        $venue->update($request->only(['name', 'city_id', 'latitude', 'longitude']));

        return response()->json([
            'status' => 'success',
            'data' => $venue->load('city')
        ]);
    }

    public function destroy($id)
    {
        $venue = Venue::find($id);

        if (!$venue) {
            return response()->json([
                'status' => 'error',
                'message' => 'Venue not found'
            ], 404);
        }

        $venue->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Venue deleted successfully'
        ]);
    }
}
