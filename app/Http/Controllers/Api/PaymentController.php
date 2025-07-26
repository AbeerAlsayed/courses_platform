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

            return redirect($response->redirectUrl);

        } catch (\App\Domains\Payments\Exceptions\InvalidCoursePriceException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\App\Domains\Payments\Exceptions\CourseAlreadyPurchasedException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Payment processing failed. Please try again.');
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
