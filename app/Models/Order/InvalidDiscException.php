<?php

namespace App\Models\Order;

class InvalidDiscException extends UnableToCreateOrderException
{
    protected $message = 'Disc does not exist';
}
