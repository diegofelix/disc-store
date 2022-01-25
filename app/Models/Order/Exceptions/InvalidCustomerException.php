<?php

namespace App\Models\Order\Exceptions;

class InvalidCustomerException extends UnableToCreateOrderException
{
    protected $message = 'Customer does not exist';
}
