<?php

namespace App\Models\Order;

use Exception;

class UnableToReserveStockException extends Exception
{
    protected $message = 'Unable to reserve stock for this order, try again later.';
}
