<?php
// Domains/Cart/Exceptions/CourseAlreadyInCartException.php
namespace App\Domains\Cart\Exceptions;

use Exception;

class CourseAlreadyInCartException extends Exception
{
    protected $message = 'The course is already in the cart.';
}
