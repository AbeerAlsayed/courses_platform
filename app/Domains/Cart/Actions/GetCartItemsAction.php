<?php
// app/Domains/Cart/Actions/GetCartItemsAction.php

namespace App\Domains\Cart\Actions;

use App\Domains\Cart\Contracts\CartRepositoryInterface;
use App\Domains\Courses\Models\Course;
use App\Support\UserIdentifier;

class GetCartItemsAction
{
    public function __construct(
        protected CartRepositoryInterface $cart,
        protected CalculateCartTotalAction $calculateTotal
    ) {}

    public function execute(): array
    {
        $userId = UserIdentifier::get();
        $courseIds = $this->cart->get($userId);

        $courses = Course::whereIn('id', $courseIds)->paginate(10);
        $total = $this->calculateTotal->execute();

        return [
            'courses' => $courses,
            'total' => $total,
        ];
    }
}
