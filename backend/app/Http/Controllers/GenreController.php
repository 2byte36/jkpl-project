<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::with('concerts')->get();

        return response()->json([
            'status' => 'success',
            'data' => $genres
        ]);
    }

    public function show($id)
    {
        $genre = Genre::with('concerts')->find($id);

        if (!$genre) {
            return response()->json([
                'status' => 'error',
                'message' => 'Genre not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $genre
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:genres,name'
        ]);

        $genre = Genre::create([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $genre
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json([
                'status' => 'error',
                'message' => 'Genre not found'
            ], 404);
        }

        $this->validate($request, [
            'name' => 'required|string|unique:genres,name,' . $id
        ]);

        $genre->update([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $genre
        ]);
    }

    public function destroy($id)
    {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json([
                'status' => 'error',
                'message' => 'Genre not found'
            ], 404);
        }

        $genre->concerts()->detach();
        $genre->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Genre deleted successfully'
        ]);
    }
}
