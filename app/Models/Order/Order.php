<?php

namespace App\Models\Order;

use App\Models\Disc\Disc;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    /**
     * This will enable creating discs
     * with create and fill methods.
     *
     * @var string[]
     */
    protected $fillable = [
        'customer_id',
        'disc_id',
        'quantity',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function disc(): BelongsTo
    {
        return $this->belongsTo(Disc::class);
    }
}
