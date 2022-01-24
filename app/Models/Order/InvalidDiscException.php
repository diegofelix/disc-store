<?php

namespace App\Models\Order;

use Exception;

class InvalidDiscException extends Exception
{
    protected $message = 'Disc does not exist';
}
