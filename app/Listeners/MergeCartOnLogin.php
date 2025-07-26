<?php
// app/Listeners/MergeCartOnLogin.php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Domains\Cart\Contracts\CartRepositoryInterface;

class MergeCartOnLogin
{
    public function __construct(protected CartRepositoryInterface $cart) {}

    public function handle(Login $event): void
    {
        $sessionCart = session()->get('cart', []);
        $userId = $event->user->id;

        if (empty($sessionCart)) return;

        foreach (array_keys($sessionCart) as $courseId) {
            if (!$this->cart->has($userId, $courseId)) {
                $this->cart->add($userId, $courseId);
            }
        }

        session()->forget('cart');
    }
}
