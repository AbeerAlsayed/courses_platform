<?php
// Domains/Cart/Services/CartService.php
namespace App\Domains\Cart\Services;

use App\Domains\Cart\Contracts\CartRepositoryInterface;

class CartService
{
    public function __construct(private CartRepositoryInterface $cart) {}

    public function getItems(int $userId): array
    {
        return $this->cart->get($userId);
    }

    public function clearCart(int $userId): void
    {
        $this->cart->clear($userId);
    }
}
