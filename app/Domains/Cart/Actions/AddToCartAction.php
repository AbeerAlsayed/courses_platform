<?php
// Domains/Cart/Actions/AddToCartAction.php
namespace App\Domains\Cart\Actions;

use App\Domains\Cart\DTOs\AddToCartData;
use App\Domains\Cart\Contracts\CartRepositoryInterface;
use App\Domains\Cart\Exceptions\CourseAlreadyInCartException;
use App\Domains\Courses\Models\Course;

class AddToCartAction
{
    public function __construct(protected CartRepositoryInterface $cart) {}


    public function execute(AddToCartData $data): Course
    {
        $id = auth()->check() ? auth()->id() : session()->getId();

        if ($this->cart->has($id, $data->courseId)) {
            throw new CourseAlreadyInCartException();
        }

        $this->cart->add($id, $data->courseId);

        return Course::findOrFail($data->courseId);
    }
}
