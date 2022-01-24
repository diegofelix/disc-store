<?php

namespace App\Models\Disc;

use App\Models\Order\Order;
use App\Models\Order\ReservedStock;
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
        $reservedStock = $this->getReservedStock();
        $stock = $disc->getStock();

        $totalStock = $stock - $reservedStock;

        return $totalStock > 0
            ? $totalStock
            : 0;
    }

    public function getReservedStock(): int
    {
        return $this->getReservedStockModel()
            ->where(['disc_id' => 1])
            ->sum('quantity');
    }

    public function decreaseStock(Disc $disc, $quantity): bool
    {
        $disc->stock -= $quantity;

        return $disc->save();
    }

    private function getModel(): Disc
    {
        return app(Disc::class);
    }

    private function getReservedStockModel(): ReservedStock
    {
        return app(ReservedStock::class);
    }
}
