<?php

namespace App\Models\Disc;

use Illuminate\Database\Eloquent\Model;

class Disc extends Model
{
    /**
     * This will handle date fields
     * as datetime instances.
     *
     * @var string[]
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'released_at',
    ];

    /**
     * This will enable creating discs
     * with create and fill methods.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'artist',
        'released_at',
        'style',
        'stock',
    ];

    public function getReservedStock(): int
    {
        return $this->reserved_stock > 0
            ? $this->reserved_stock
            : 0;
    }

    public function getStock(): int
    {
        return $this->stock > 0
            ? $this->stock
            : 0;
    }
}
