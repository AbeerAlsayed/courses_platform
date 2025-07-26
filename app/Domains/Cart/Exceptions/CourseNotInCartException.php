<?php

namespace App\Domains\Cart\Exceptions;

use Exception;

class CourseNotInCartException extends Exception
{
    protected $message = 'The course is not in the cart.';
}
