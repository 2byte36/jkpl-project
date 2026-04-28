<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use Midtrans\Notification;
use Midtrans\Config;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Config::$isProduction = false;
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');

        $notification = new Notification();

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id; 
        $fraudStatus = $notification->fraud_status;

        $id = intval(str_replace('ORDER-', '', $orderId));

        $order = OrderDetail::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $order->status = 'paid';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $order->status = 'canceled';
        }

        $order->save();

        return response()->json(['message' => 'Notification processed'], 200);
    }
}
