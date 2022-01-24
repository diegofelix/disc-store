<?php

namespace App\Models\Order;

use App\Models\User\Repository as CustomerRepository;
use Illuminate\Support\Collection;

class Repository
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
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

    public function create(array $attributes): ?Order
    {
        if (!$this->customerIsInvalid($attributes)) {
            return null;
        }

        $disc = $this->getModel();
        $disc->fill($attributes);

        return $disc->save() ? $disc : null;
    }

    private function getModel(): Order
    {
        return app(Order::class);
    }

    private function customerIsInvalid(array $attributes): bool
    {
        if (!$customerId = $attributes['customer_id'] ?? false) {
            return false;
        }

        return (bool) $this->customerRepository->findById($customerId);
    }
}
