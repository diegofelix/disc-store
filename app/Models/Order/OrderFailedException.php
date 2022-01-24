<?php

namespace App\Models\Order;

use Exception;

class OrderFailedException extends Exception
{
    protected $message = 'Error when creating a new Order';
}
