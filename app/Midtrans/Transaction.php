<?php

namespace App\Midtrans;

use Midtrans\ApiRequestor;
use Midtrans\Config;
use Midtrans\Transaction as MidtransTransaction;

class Transaction extends MidtransTransaction
{
    /**
     * Retrieve transaction status
     *
     * @param  string  $id Order ID or transaction ID
     * @return object
     *
     * @throws Exception
     */
    public static function status($id)
    {
        return ApiRequestor::get(
            Config::getBaseUrl().'/v2/'.$id.'/status',
            Config::$serverKey,
            false
        );
    }
}
