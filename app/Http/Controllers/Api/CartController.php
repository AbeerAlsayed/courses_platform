<?php

namespace App\Http\Controllers\Api;

use App\Domains\Cart\Actions\AddToCartAction;
use App\Domains\Cart\Actions\CalculateCartTotalAction;
use App\Domains\Cart\Actions\GetCartItemsAction;
use App\Domains\Cart\Contracts\CartRepositoryInterface;
use App\Domains\Cart\DTOs\AddToCartData;
use App\Domains\Cart\Exceptions\CourseNotInCartException;
use App\Domains\Courses\Models\Course;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Support\UserIdentifier;
use Illuminate\Http\Request;


class CartController extends Controller
{
    public function index(GetCartItemsAction $action)
    {
        $result = $action->execute();

        return CartItemResource::collection($result['courses'])
            ->additional(['total' => $result['total']]);
    }

    public function store(Request $request, AddToCartAction $action)
    {
        $request->validate([
            'course_id' => ['required', 'integer'],
        ]);

        $userId = UserIdentifier::get();

        $course = $action->execute(new AddToCartData(
            userId: $userId,
            courseId: $request->course_id
        ));

        return response()->json([
            'message' => 'Course added to cart.',
            'course' => new CartItemResource($course),
        ], 201);
    }

    public function destroy(int $courseId, CartRepositoryInterface $cart)
    {
        $id = UserIdentifier::get();

        if (! $cart->has($id, $courseId)) {
            throw new CourseNotInCartException();
        }

        $cart->remove($id, $courseId);

        return response()->json([
            'message' => 'Course removed from cart.',
            'course_id' => $courseId,
        ]);
    }

}
