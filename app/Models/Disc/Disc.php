<?php

namespace App\Models\Disc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disc extends Model
{
    use HasFactory;

    protected $dates = [
        'created_at',
        'updated_at',
        'released_at',
    ];

    protected $fillable = [
        'name',
        'artist',
        'released_at',
        'style',
        'stock',
    ];
}
