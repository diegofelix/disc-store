<?php

namespace App\Models\Disc;

use Illuminate\Support\Collection;

class Repository
{
    public function list(): Collection
    {
        return $this->getModel()->all();
    }

    private function getModel(): Disc
    {
        return app(Disc::class);
    }
}
