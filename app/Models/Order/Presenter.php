<?php

namespace App\Models\Order;

use App\Models\Disc\Presenter as DiscPresenter;
use App\Models\User\Presenter as CustomerPresenter;
use Illuminate\Support\Collection;

class Presenter
{
    /**
     * @var DiscPresenter
     */
    private $discPresenter;
    /**
     * @var CustomerPresenter
     */
    private $customerPresenter;

    public function __construct(DiscPresenter $discPresenter, CustomerPresenter $customerPresenter)
    {
        $this->discPresenter = $discPresenter;
        $this->customerPresenter = $customerPresenter;
    }

    public function present(Collection $orders): array
    {
        foreach ($orders as $order) {
            $data[] = [
                'id' => $order->id,
                'status' => $order->status,
                'disc_id' => $order->disc_id,
                'customer_id' => $order->customer_id,
                'quantity' => $order->quantity,
                'created_at' => $order->created_at->format('Y-m-d H:i'),
            ];
        }

        return $data ?? [];
    }

    public function presentSingleOrder(Order $order): array
    {
        $customer = $order->customer;
        $disc = $order->disc;

        return [
            'id' => $order->id,
            'status' => $order->status,
            'customer' => $this->customerPresenter->presentSingleUser($customer),
            'disc' => $this->discPresenter->presentSingleDisc($disc),
            'quantity' => $order->quantity,
            'created_at' => $order->created_at->format('Y-m-d H:i'),
        ];
    }
}
