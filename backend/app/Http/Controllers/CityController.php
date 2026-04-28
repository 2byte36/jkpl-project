<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('venues')->get();

        return response()->json([
            'status' => 'success',
            'data' => $cities
        ]);
    }

    public function show($id)
    {
        $city = City::with('venues')->find($id);

        if (!$city) {
            return response()->json([
                'status' => 'error',
                'message' => 'City not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $city
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:cities,name'
        ]);

        $city = City::create([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $city
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json([
                'status' => 'error',
                'message' => 'City not found'
            ], 404);
        }

        $this->validate($request, [
            'name' => 'required|string|unique:cities,name,' . $id
        ]);

        $city->update([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $city
        ]);
    }

    public function destroy($id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json([
                'status' => 'error',
                'message' => 'City not found'
            ], 404);
        }

        $city->venues()->delete();

        $city->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'City deleted successfully'
        ]);
    }
}
