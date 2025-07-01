<?php

namespace App\Http\Controllers\Api;

use App\Domains\Cart\Actions\AddToCartAction;
use App\Domains\Cart\Contracts\CartRepositoryInterface;
use App\Domains\Cart\DTOs\AddToCartData;
use App\Domains\Cart\Exceptions\CourseNotInCartException;
use App\Domains\Courses\Models\Course;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use Illuminate\Http\Request;


class CartController extends Controller
{
    public function index(CartRepositoryInterface $cart)
    {
        $id = auth()->check() ? auth()->id() : session()->getId();
        $courseIds = $cart->get($id);


        $courses = Course::whereIn('id', $courseIds)->get();

        return CartItemResource::collection($courses);
    }

    public function store(Request $request, AddToCartAction $action)
    {
        $request->validate([
            'course_id' => ['required', 'integer'],
        ]);

        $userId = auth()->check() ? auth()->id() : session()->getId();

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
        $id = auth()->check() ? auth()->id() : session()->getId();

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
