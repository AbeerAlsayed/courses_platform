<?php


namespace App\Domains\Courses\Actions\Courses;

use App\Domains\Courses\DTOs\CourseData;
use App\Domains\Courses\Enums\CourseStatus;
use App\Domains\Courses\Models\Course;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;

class CreateCourseAction
{
    public function execute(CourseData $data): Course
    {
        $createData = $data->toArray();

        if (Auth::user()->hasRole('instructor')) {
            $createData['status'] = CourseStatus::Pending->value;
        }

        if ($createData['price'] > 0) {
            Stripe::setApiKey(config('services.stripe.secret'));

            $product = Product::create([
                'name' => $createData['title'],
            ]);

            $price = Price::create([
                'product' => $product->id,
                'unit_amount' => intval($createData['price'] * 100), // بالسينت
                'currency' => $createData['currency'] ?? 'usd',
            ]);

            // أضف السطر التالي لحفظ stripe_product_id
            $createData['stripe_product_id'] = $product->id;
            $createData['stripe_price_id'] = $price->id;
        }


        return Course::create($createData);
    }
}
