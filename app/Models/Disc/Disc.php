<?php

namespace App\Disc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disc extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'artist',
        'released_at',
        'style',
        'stock',
    ];
}
