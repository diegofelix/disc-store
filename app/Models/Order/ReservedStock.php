<?php

namespace App\Models\Order;

use App\Models\Disc\Disc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservedStock extends Model
{
    protected $fillable = [
        'order_id',
        'disc_id',
        'quantity',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function disc(): BelongsTo
    {
        return $this->belongsTo(Disc::class);
    }
}
