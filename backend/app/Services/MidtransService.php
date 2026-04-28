<?php

namespace App\Services;

use Midtrans\Config;

class MidtransService
{
    public static function init()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
}
