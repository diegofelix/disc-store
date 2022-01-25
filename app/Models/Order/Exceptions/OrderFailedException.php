<?php

namespace App\Models\Order\Exceptions;

class OrderFailedException extends UnableToCreateOrderException
{
    protected $message = 'Error when creating a new Order';
}
