<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with('concert');
        if ($request->has('concert_id')) {
            $query->where('concert_id', $request->concert_id);
        }

        $tickets = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $tickets
        ]);
    }

    public function show($id)
    {
        $ticket = Ticket::with('concert')->find($id);

        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ticket not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $ticket
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'concert_id' => 'required|exists:concerts,id',
            'name' => 'required|string',
            'price' => 'required|integer|min:0',
            'quota' => 'required|integer|min:1',
            'sales_start' => 'required|date',
            'sales_end' => 'required|date|after_or_equal:sales_start',
        ]);

        $ticket = Ticket::create($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $ticket->load('concert')
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ticket not found'
            ], 404);
        }

        $this->validate($request, [
            'concert_id' => 'sometimes|exists:concerts,id',
            'name' => 'sometimes|string',
            'price' => 'sometimes|integer|min:0',
            'quota' => 'sometimes|integer|min:1',
            'sales_start' => 'sometimes|date',
            'sales_end' => 'sometimes|date|after_or_equal:sales_start',
        ]);

        $ticket->update($request->only([
            'concert_id',
            'name',
            'price',
            'quota',
            'sales_start',
            'sales_end'
        ]));

        return response()->json([
            'status' => 'success',
            'data' => $ticket->load('concert')
        ]);
    }

    public function destroy($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ticket not found'
            ], 404);
        }

        $ticket->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket deleted successfully'
        ]);
    }
}
