<?php

namespace App\Models\Order\Exceptions;

class InvalidQuantityException extends UnableToCreateOrderException
{
    protected $message = 'There is no stock for the disc selected.';
}
