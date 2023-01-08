<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Midtrans\Transaction;
use App\Models\Order;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = App::environment('production');
    }

    public function index()
    {
        return Inertia::render('Order/Index');
    }

    public function create()
    {
        $order = new Order;
        $order->gross_amount = 15000;
        $order->save();

        $order->order_id = 'SB-GSNAP-' . $order->id;
        $order->save();

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_id,
                'gross_amount' => $order->gross_amount,
            ],
            'enabled_payments' => ['shopeepay'],
            'expiry' => [
                'unit' => 'hour',
                'duration' => 1
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return Inertia::render('Order/Pay', [
            'scriptSrc' => Config::$isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js',
            'clientKey' => config('midtrans.client_key'),
            'snapToken' => $snapToken,
            'order' => $order,
        ]);
    }

    public function show(Order $order)
    {
        // if belum settlement
        if ($order->status !== OrderStatus::SETTLEMENT->value) {
            // dan bukan pending juga
            if ($order->status !== OrderStatus::PENDING->value) abort(403); // sudah expired

            // if pending coba update ke midtrans
            $order->update(['status' => Transaction::status($order->order_id)->transaction_status]);

            // if setelah update masih belum settlement
            if ($order->status !== OrderStatus::SETTLEMENT->value) abort(403); // barusan expired
        }

        return Redirect::route('photo-papers.create', ['order_id' => $order]);
    }
}
