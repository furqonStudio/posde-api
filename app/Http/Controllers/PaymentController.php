<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:' . $order->total_price,
        ]);

        if ($order->payment) {
            return response()->json(['error' => 'Order already paid'], 400);
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'payment_status' => 'paid',
        ]);

        $order->update(['status' => 'completed']);

        return response()->json($payment, 201);
    }
}
