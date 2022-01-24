<?php

namespace App\Models\Order;

use Exception;

class InvalidCustomerException extends Exception
{
    protected $message = 'Customer does not exist';
}
