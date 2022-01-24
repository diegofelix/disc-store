<?php

namespace App\Models\Disc;

use App\Models\Order\Order;
use DateTime;
use Illuminate\Support\Collection;

class Repository
{
    public function list(?array $filters = []): Collection
    {
        $now = (new DateTime())->format('Y-m-d H:i');
        $query = $this->getModel()->where('released_at', '<=', $now);

        foreach ($filters as $filterKey => $value) {
            $operator = '=';

            // When filtering by release date
            // we may want to show all discs from that period.
            if ($filterKey === 'released_at') {
                $operator = '>=';
            }

            $query = $query->where($filterKey, $operator, $value);
        }

        return $query->get();
    }

    public function findById(string $id): ?Disc
    {
        return $this->getModel()->find($id);
    }

    public function create(array $attributes): ?Disc
    {
        $disc = $this->getModel();
        $disc->fill($attributes);

        return $disc->save() ? $disc : null;
    }

    private function getModel(): Disc
    {
        return app(Disc::class);
    }

    public function destroy(Disc $disc): bool
    {
        return $disc->delete();
    }

    public function discHasStock(Disc $disc, int $quantity): bool
    {
        $totalStock = $this->calculateStockWithReserved($disc);

        return $totalStock >= $quantity;
    }

    private function calculateStockWithReserved(Disc $disc)
    {
        $reservedStock = $disc->getReservedStock();
        $stock = $disc->getStock();

        $totalStock = $stock - $reservedStock;

        return $totalStock > 0
            ? $totalStock
            : 0;
    }

    public function reserveFor(Disc $disc, Order $order): bool
    {
        $disc->reserved_stock = $order->quantity;

        return $disc->save();
    }

    public function releaseReservedStockFor(Disc $disc, Order $order): bool
    {
        if (!$this->discHasStock($disc, $order->quantity)) {
            return false;
        }

        $disc->stock = $disc->stock - $order->quantity;
        $disc->reserved_stock = $disc->reserved_stock - $order->quantity;

        return $disc->save();
    }
}
