<?php

namespace App\Domains\Cart\Actions;

use App\Domains\Cart\Contracts\CartRepositoryInterface;
use App\Domains\Courses\Models\Course;
use App\Support\UserIdentifier;

class CalculateCartTotalAction
{
    public function __construct(protected CartRepositoryInterface $cart) {}

    public function execute(): float
    {
        $userId = UserIdentifier::get();
        $courseIds = $this->cart->get($userId);

        return Course::whereIn('id', $courseIds)->sum('price');
    }
}
