<?php

namespace App\Http\Controllers;

use App\Models\TicketOrder;
use Illuminate\Http\Request;
use App\Services\MidtransService;
use Midtrans\Snap;
use App\Models\OrderDetail;
use App\Models\Ticket;
use Carbon\Carbon;


class TicketOrderController extends Controller
{
    public function index()
    {
        $ticketOrders = TicketOrder::with(['ticket', 'orderDetail'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $ticketOrders
        ]);
    }

    public function show($id)
    {
        $ticketOrder = TicketOrder::with(['ticket', 'orderDetail'])->find($id);

        if (!$ticketOrder) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ticket order not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $ticketOrder
        ]);
    }

    public function store(Request $request)
    {
        MidtransService::init();

        $this->validate($request, [
            'tickets' => 'required|array|min:1',
            'tickets.*.ticket_id' => 'required|exists:tickets,id',
            'tickets.*.quantity' => 'required|integer|min:1'
        ]);

        $user = $request->user;

        $orderDetail = OrderDetail::create([
            'user_id' => $user->id,
            'order_time' => Carbon::now(),
            'status' => 'pending',
        ]);

        $totalAmount = 0;

        foreach ($request->tickets as $item) {
            $ticket = Ticket::findOrFail($item['ticket_id']);

            if ($item['quantity'] > $ticket->quota) {
                return response()->json([
                    'message' => "Not enough quota for ticket ID {$ticket->id}"
                ], 400);
            }

            TicketOrder::create([
                'ticket_id' => $ticket->id,
                'order_detail_id' => $orderDetail->id,
                'quantity' => $item['quantity'],
            ]);

            $ticket->quota -= $item['quantity'];
            $ticket->save();

            $totalAmount += $ticket->price * $item['quantity'];
        }

        $totalAmount = intval(round($totalAmount * 1.025));

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $orderDetail->id . '-' . uniqid(),
                'gross_amount' => $totalAmount,
            ],
            'customer_details' => [
                'first_name' => $user->username,
                'email' => $user->email,
            ],
        ];

        try {
            $transaction = Snap::createTransaction($params);
            $snapUrl = $transaction->redirect_url;

            $orderDetail->transaction_id = $params['transaction_details']['order_id'];
            $orderDetail->save();

            return response()->json([
                'message' => 'Order created',
                'snap_url' => $snapUrl
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $ticketOrder = TicketOrder::find($id);

        if (!$ticketOrder) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ticket order not found'
            ], 404);
        }

        $this->validate($request, [
            'ticket_id' => 'sometimes|exists:tickets,id',
            'order_detail_id' => 'sometimes|exists:order_details,id',
            'quantity' => 'sometimes|integer|min:1'
        ]);

        $ticketOrder->update($request->only(['ticket_id', 'order_detail_id', 'quantity']));

        return response()->json([
            'status' => 'success',
            'data' => $ticketOrder->load(['ticket', 'orderDetail'])
        ]);
    }

    public function destroy($id)
    {
        $ticketOrder = TicketOrder::find($id);

        if (!$ticketOrder) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ticket order not found'
            ], 404);
        }

        $ticketOrder->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket order deleted successfully'
        ]);
    }
}
