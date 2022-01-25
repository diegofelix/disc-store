<?php

namespace App\Models\Order\Exceptions;

class UnableToReserveStockException extends UnableToCreateOrderException
{
    protected $message = 'Unable to reserve stock for this order, try again later.';
}
