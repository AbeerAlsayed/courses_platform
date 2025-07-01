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

class PaymentController extends Controller
{

    public function createCheckout(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);
//
        $user = Auth::user();
        $course = Course::findOrFail($request->course_id);

        // تحقق إذا اشترى الكورس من قبل
        $alreadyPaid = Payment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'success')
            ->exists();

        if ($alreadyPaid) {
            return response()->json([
                'message' => 'لقد قمت بشراء هذا الكورس من قبل.'
            ], 400);
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd', // أو عملتك
                    'product_data' => [
                        'name' => $course->title,
                    ],
                    'unit_amount' => intval($course->price * 100), // السعر بالسنت
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/success'),
            'cancel_url' => url('/cancel'),
            'metadata' => [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
        ]);

        // سجل الدفع مبدئيًا بحالة pending
        $payment = Payment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'amount' => $course->price,
            'payment_provider' => 'stripe',
            'provider_reference' => $session->id,
            'status' => 'pending',
        ]);

        return response()->json([
            'checkout_url' => $session->url,
            'payment_id' => $payment->id,
        ]);
    }
//    public function createCheckout(Request $request)
//    {
//        try {
//            $request->validate([
//                'course_id' => 'required|exists:courses,id',
//            ]);
//
//            $course = Course::findOrFail($request->course_id);
//
//            if ($request->user()->courses()->where('course_id', $course->id)->exists()) {
//                return response()->json(['message' => 'لقد اشتريت هذا الكورس من قبل.'], 400);
//            }
//
//            Stripe::setApiKey(config('services.stripe.secret'));
//
//            $session = StripeSession::create([
//                'payment_method_types' => ['card'],
//                'customer_email' => $request->user()->email,
//                'line_items' => [[
//                    'price' => $course->stripe_price_id,
//                    'quantity' => 1,
//                ]],
//                'mode' => 'payment',
//                'success_url' => url('/payment/success'),
//                'cancel_url' => url('/payment/cancel'),
//                'metadata' => [
//                    'user_id' => $request->user()->id,
//                    'course_id' => $course->id,
//                ],
//            ]);
//
//            return response()->json(['checkout_url' => $session->url]);
//
//        } catch (\Stripe\Exception\ApiErrorException $e) {
//            Log::error('Stripe API Error', [
//                'message' => $e->getMessage(),
//                'stripe_error' => $e->getJsonBody(),
//            ]);            return response()->json(['message' => 'حدث خطأ أثناء إنشاء عملية الدفع.'], 500);
//        } catch (\Exception $e) {
//            Log::error('Checkout Error: ' . $e->getMessage());
//            return response()->json(['message' => 'فشل في معالجة الطلب.'], 500);
//        }
//    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $signature, config('services.stripe.webhook_secret'));

            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;

                DB::transaction(function () use ($session) {
                    $userId = $session->metadata->user_id ?? null;
                    $courseId = $session->metadata->course_id ?? null;

                    if (!$userId || !$courseId) {
                        Log::error('Missing metadata in Stripe session.');
                        return;
                    }

                    Payment::create([
                        'user_id' => $userId,
                        'course_id' => $courseId,
                        'amount' => $session->amount_total / 100,
                        'payment_provider' => 'stripe',
                        'provider_reference' => $session->payment_intent,
                        'status' => 'succeeded',
                    ]);

                    DB::table('course_user')->updateOrInsert([
                        'user_id' => $userId,
                        'course_id' => $courseId,
                    ], [
                        'enrolled_at' => now(),
                        'updated_at' => now(),
                    ]);
                });
            }

            return response()->json(['status' => 'تمت العملية بنجاح']);
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe payload error: ' . $e->getMessage());
            return response()->json(['error' => 'خطأ في البيانات المستلمة من Stripe'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe signature error: ' . $e->getMessage());
            return response()->json(['error' => 'توقيع Stripe غير صالح'], 400);
        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ داخلي'], 500);
        }
    }

}
