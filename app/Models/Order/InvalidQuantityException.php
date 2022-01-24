<?php

namespace App\Models\Order;

use Exception;

class InvalidQuantityException extends Exception
{
    protected $message = 'There is no stock for the disc selected.';
}
