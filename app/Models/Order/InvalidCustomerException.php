<?php

namespace App\Models\Order;

use Exception;

class InvalidCustomerException extends UnableToCreateOrderException
{
    protected $message = 'Customer does not exist';
}
