<?php

namespace App\Http\Controllers\Api;

use App\Domains\Courses\Models\Course;
use App\Domains\Payments\Models\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Illuminate\Support\Facades\DB;
use App\Domains\Payments\Actions\StartPaymentAction;


class PaymentController extends Controller
{
    public function __construct(
        private StartPaymentAction $startPaymentAction
    ) {}

    public function initiate(Course $course)
    {
        try {
            $response = $this->startPaymentAction->execute(
                auth()->user(),
                $course
            );

            // بدل redirect، نرسل JSON مع الرابط
            return response()->json([
                'success' => true,
                'redirect_url' => $response->redirectUrl,
            ]);

        } catch (\App\Domains\Payments\Exceptions\InvalidCoursePriceException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\App\Domains\Payments\Exceptions\CourseAlreadyPurchasedException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed. Please try again.',
            ], 500);
        }
    }

    public function success(Payment $payment)
    {
        return view('payment-success', compact('payment'));
    }

    public function cancel(Payment $payment)
    {
        return view('payment-failed', compact('payment'));
    }
}
