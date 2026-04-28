<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\SupabaseService;
use Carbon\Carbon;

class ConcertController extends Controller
{
    public function index(Request $request)
    {
        $query = Concert::with(['genres', 'venue.city', 'tickets']);

        // Filter: Date Range
        if ($request->has(['start_date', 'end_date'])) {
            $query->whereBetween('concert_start', [$request->start_date, $request->end_date]);
        }

        // Filter: City
        if ($request->has('city_id')) {
            $query->whereHas('venue', function ($q) use ($request) {
                $q->where('city_id', $request->city_id);
            });
        }

        $limit = "3";
        if ($request->has('limit')) {
            $limit = $request->limit;
        }

        // Filter: Genre
        if ($request->has('genre_ids')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->whereIn('genres.id', $request->genre_ids);
            });
        }

        // Filter: Price Range (dari relasi ticket)
        if ($request->has(['min_price', 'max_price'])) {
            $query->whereHas('tickets', function ($q) use ($request) {
                $q->whereBetween('price', [$request->min_price, $request->max_price]);
            });
        }

        if ($request->has("upcoming") && $request->upcoming == 1) {
            $nextYear = Carbon::now()->addYear()->year;
            $query->whereYear('concert_start', $nextYear);
        }

        if ($request->has('trending') && $request->trending == 1) {
            $query->withCount(['tickets as total_sold' => function ($q) {
                $q->join('ticket_orders', 'tickets.id', '=', 'ticket_orders.ticket_id')
                    ->select(DB::raw('SUM(ticket_orders.quantity)'));
            }])->orderByDesc('total_sold');
        }

        // Filter : This Week
        if ($request->has('this_week') && $request->this_week == 1) {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            $query->whereBetween('concert_start', [$startOfWeek, $endOfWeek]);
        }

        // Filter : Concerts Today
        if ($request->has('today') && $request->today == 1) {
            $today = Carbon::now()->format('Y-m-d');
            $query->whereDate('concert_start', $today);
        }

        // Filter: Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhereHas('venue', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            });
        }

        // Pagination
        $perPage = $request->get('limit', $limit);
        $concerts = $query->paginate($perPage);

        // Transformasi koleksi untuk menambahkan min_price dan max_price
        $transformed = $concerts->map(function ($concert) {
            $prices = $concert->tickets->pluck('price');
            $concert->min_price = $prices->min();
            $concert->max_price = $prices->max();
            return $concert;
        });

        // Ganti collection bawaan dengan yang sudah ditransformasi
        $concerts->setCollection($transformed);

        return response()->json([
            'status' => 'success',
            'data' => $concerts
        ]);
    }

    public function show($id)
    {
        $concert = Concert::with(['genres', 'venue'])->find($id);

        if (!$concert) {
            return response()->json([
                'status' => 'error',
                'message' => 'Concert not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $concert
        ]);
    }

    public function store(Request $request, SupabaseService $supabase)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'concert_start' => 'required|date',
            'concert_end' => 'required|date',
            'venue_id' => 'required|integer',
            'link_poster' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'link_venue' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'genre_ids' => 'required|array',
        ]);

        $linkPoster = null;
        if ($request->hasFile('link_poster')) {
            $linkPoster = $supabase->upload($request->file('link_poster'), 'posters');
        }

        $linkVenue = null;
        if ($request->hasFile('link_venue')) {
            $linkVenue = $supabase->upload($request->file('link_venue'), 'venues');
        }

        $concert = Concert::create([
            'name' => $request->name,
            'description' => $request->description,
            'concert_start' => $request->concert_start,
            'concert_end' => $request->concert_end,
            'venue_id' => $request->venue_id,
            'link_poster' => $linkPoster,
            'link_venue' => $linkVenue,
        ]);


        $concert->genres()->attach($request->genre_ids);

        return response()->json([
            'status' => 'success',
            'data' => $concert->load(['genres', 'venue'])
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $concert = Concert::find($id);

        if (!$concert) {
            return response()->json([
                'status' => 'error',
                'message' => 'Concert not found'
            ], 404);
        }

        $this->validate($request, [
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'concert_start' => 'sometimes|date',
            'concert_end' => 'sometimes|date',
            'venue_id' => 'sometimes|integer',
            'link_poster' => 'nullable|url',
            'link_venue' => 'nullable|url',
            'genre_ids' => 'nullable|array'
        ]);

        $concert->update($request->only([
            'name',
            'description',
            'concert_start',
            'concert_end',
            'venue_id',
            'link_poster',
            'link_venue'
        ]));

        if ($request->has('genre_ids')) {
            $concert->genres()->sync($request->genre_ids);
        }

        return response()->json([
            'status' => 'success',
            'data' => $concert->load(['genres', 'venue'])
        ]);
    }

    public function destroy($id)
    {
        $concert = Concert::find($id);

        if (!$concert) {
            return response()->json([
                'status' => 'error',
                'message' => 'Concert not found'
            ], 404);
        }

        $concert->genres()->detach();

        $concert->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Concert deleted successfully'
        ]);
    }
}
