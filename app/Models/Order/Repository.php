<?php

namespace App\Models\Order;

use DateTime;
use Illuminate\Support\Collection;

class Repository
{
    public function list(?array $filters = []): Collection
    {
        $query = $this->getModel();

        foreach ($filters as $filterKey => $value) {
            $operator = '=';

            if ($filterKey === 'from') {
                $operator = '>=';
            }

            if ($filterKey === 'until') {
                $operator = '<=';
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
        $disc = $this->getModel();
        $disc->fill($attributes);

        return $disc->save() ? $disc : null;
    }

    private function getModel(): Order
    {
        return app(Order::class);
    }
}
