<?php

namespace App\Models\Disc;

use Illuminate\Support\Collection;

class Repository
{
    public function list(?array $filters = []): Collection
    {
        $query = $this->getModel();

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
}
