<?php

namespace App\Domains\Payments\Exceptions;

class CourseAlreadyPurchasedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('You have already purchased this course.');
    }
}
