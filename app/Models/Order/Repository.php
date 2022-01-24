<?php

namespace App\Models\Order;

use App\Models\Disc\Disc;
use App\Models\Disc\Repository as DiscRepository;
use App\Models\User\Repository as CustomerRepository;
use App\Models\User\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Repository
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @var DiscRepository
     */
    private $discRepository;

    public function __construct(CustomerRepository $customerRepository, DiscRepository $discRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->discRepository = $discRepository;
    }

    public function list(?array $filters = []): Collection
    {
        $query = $this->getModel();

        foreach ($filters as $filterKey => $value) {
            $operator = '=';

            if (in_array($filterKey, ['from', 'until'])) {
                $operator = $filterKey === 'from' ? '>=' : '<';
                $query = $query->where('created_at', $operator, $value);

                continue;
            }

            $query = $query->where($filterKey, $operator, $value);
        }

        return $query->get();
    }

    public function findById(string $id): ?Order
    {
        return $this->getModel()->find($id);
    }

    public function create(array $attributes): Order
    {
        $order = $this->getModel();
        $disc = $this->getDisc($attributes);
        $customer = $this->getCustomer($attributes);
        $this->validateQuantity($disc, $attributes);
        $order->customer()->associate($customer);
        $order->disc()->associate($disc);
        $order->quantity = $attributes['quantity'];
        $order->status = Order::STATUS_PROCESSING;

        // This Database transaction makes sure that if we cannot
        // create an order, we will not be able to reserve stock too.
        // This avoids reserving stock for orders that does not exist.
        DB::transaction(function () use ($order, $disc, $customer, $attributes)  {
            if (!$order->save()) {
                throw new OrderFailedException();
            }

            if (!$this->discRepository->reserveFor($disc, $order)) {
                throw new UnableToReserveStockException();
            }
        });

        return $order;
    }

    private function getModel(): Order
    {
        return app(Order::class);
    }

    private function getCustomer(array $attributes): User
    {
        $customerId = $attributes['customer_id'] ?? '';
        if (!$customer = $this->customerRepository->findById($customerId)) {
            throw new InvalidCustomerException();
        }

        return $customer;
    }

    private function getDisc(array $attributes): Disc
    {
        $discId = $attributes['disc_id'] ?? '';
        if (!$customer = $this->discRepository->findById($discId)) {
            throw new InvalidDiscException();
        }

        return $customer;
    }

    private function validateQuantity(Disc $disc, array $attributes): void
    {
        $quantity = $attributes['quantity'];

        if (!$this->discRepository->discHasStock($disc, $quantity)) {
            throw new InvalidQuantityException();
        }
    }
}
