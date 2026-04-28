<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderDetailController extends Controller
{
    public function index()
    {
        $orders = OrderDetail::with(['user', 'ticketOrders'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }

    public function me(Request $request)
{
    $user = $request->user;
    $now = Carbon::now();

    $orders = OrderDetail::with(['ticketOrders.ticket.concert'])
        ->where('user_id', $user->id);

    if ($request->has('past')) {
        $isPast = $request->query('past');
        $orders = $orders->whereHas('ticketOrders.ticket.concert', function ($query) use ($isPast, $now) {
            if ($isPast == 1) {
                $query->where('concert_end', '<', $now);
            } else {
                $query->where('concert_end', '>=', $now);
            }
        });
    }

    $limit = $request->get('limit', 3);
    $orders = $orders->paginate($limit);

    return response()->json([
        'status' => 'success',
        'data' => $orders
    ]);
}


    public function show($id)
    {
        $order = OrderDetail::with(['user', 'ticketOrders'])->find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $order
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'order_time' => 'required|date',
            'status' => 'in:pending,paid,canceled'
        ]);

        $order = OrderDetail::create([
            'user_id' => $request->user_id,
            'order_time' => $request->order_time,
            'status' => $request->status ?? 'pending'
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $order->load('user')
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $order = OrderDetail::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        $this->validate($request, [
            'user_id' => 'sometimes|exists:users,id',
            'order_time' => 'sometimes|date',
            'status' => 'sometimes|in:pending,paid,canceled'
        ]);

        $order->update($request->only(['user_id', 'order_time', 'status']));

        return response()->json([
            'status' => 'success',
            'data' => $order->load('user')
        ]);
    }

    public function destroy($id)
    {
        $order = OrderDetail::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        $order->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Order deleted successfully'
        ]);
    }
}
