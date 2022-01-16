<?php

namespace App\Models\Disc;

use Illuminate\Support\Collection;

class Repository
{
    public function list(): Collection
    {
        return Disc::all();
    }
}
