<?php

namespace App\Http\Controllers\Api;

use App\Domains\Courses\Models\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class StripeController extends Controller
{

    public function checkout(Request $request)
{
    $course = Course::findOrFail($request->course_id);

    Stripe::setApiKey(config('services.stripe.secret'));

    $session = StripeSession::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $course->price * 100,
                'product_data' => [
                    'name' => $course->title,
                    'description' => $course->description,
                ],
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => route('payment.failed'),
        'metadata' => [
            'user_id' => auth()->id(),
            'course_id' => $course->id,
        ],
    ]);

    return response()->json(['url' => $session->url]);
}
    public function success(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::retrieve($request->session_id);
        $userId = $session->metadata->user_id;
        $courseId = $session->metadata->course_id;

        DB::table('course_user')->updateOrInsert([
            'user_id' => $userId,
            'course_id' => $courseId,
        ], [
            'enrolled_at' => now(),
        ]);
        return view('payment-success');
    }
    public function failed()
    {

        return view('payment-failed');
    }
}
