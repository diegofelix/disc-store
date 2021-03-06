<?php

namespace App\Models\Disc;

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
}
