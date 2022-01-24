<?php

namespace App\Models\Order;

use App\Models\Disc\Repository as DiscRepository;
use Illuminate\Support\Facades\Log;

class Processor
{
    /**
     * @var DiscRepository
     */
    private $discRepository;

    public function __construct(DiscRepository $repository)
    {
        $this->discRepository = $repository;
    }

    public function process(Order $order)
    {
        $disc = $this->discRepository->findById($order->disc_id);

        // Cancels orders that do not have stock anymore.
        if (!$this->discRepository->releaseReservedStockFor($disc, $order)) {
            $order->status = Order::STATUS_CANCELED;
        }

        $order->status = Order::STATUS_SUCCESS;
        $order->save();
    }
}
