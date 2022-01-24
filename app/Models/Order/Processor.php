<?php

namespace App\Models\Order;

class Processor
{
    /**
     * @var Repository
     */
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function process(Order $order)
    {
        // Cancels orders that do not have stock anymore.
        if (!$this->repository->releaseReservedStockFor($order)) {
            $order->status = Order::STATUS_CANCELED;
        }

        $order->status = Order::STATUS_SUCCESS;
        $order->save();
    }
}
