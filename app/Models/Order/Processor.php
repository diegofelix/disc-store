<?php

namespace App\Models\Order;

use Illuminate\Support\Facades\Log;

class Processor
{
    public function process(Order $order)
    {
        Log::info('order is being processed on worker', [
            'order' => $order->id,
        ]);
    }
}
