<?php

namespace App\Domains\Courses\Services;

use App\Domains\Courses\DTOs\StripeProductResult;
use Stripe\Exception\ApiErrorException;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;

class StripeProductService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a Stripe Product + Price and return the IDs.
     *
     * @throws \RuntimeException on Stripe API failure
     */
    public function createProduct(string $title, float $price): StripeProductResult
    {
        try {
            $product = Product::create(['name' => $title]);

            $stripePrice = Price::create([
                'product'     => $product->id,
                'unit_amount' => (int) round($price * 100), // Convert to cents
                'currency' => 'eur',
            ]);

            return new StripeProductResult(
                productId: $product->id,
                priceId:   $stripePrice->id,
            );
        } catch (ApiErrorException $e) {
            // Log internally — never expose Stripe error details to the client
            report($e);
            throw new \RuntimeException('Payment provider error. Please try again later.');
        }
    }
}
