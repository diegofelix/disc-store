<?php

namespace App\Models\Order;

class InvalidQuantityException extends UnableToCreateOrderException
{
    protected $message = 'There is no stock for the disc selected.';
}
