<?php

namespace App\Models\Order\Exceptions;

class InvalidDiscException extends UnableToCreateOrderException
{
    protected $message = 'Disc does not exist';
}
