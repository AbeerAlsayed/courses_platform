<?php

namespace App\Http\Controllers\Api;

use App\Domains\Interactions\DTOs\RatingDTO;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Interactions\Actions\CreateRatingAction;
use Illuminate\Support\Facades\Auth;

class CourseRatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $data = new RatingDTO(
            userId: Auth::id(),
            courseId: $request->course_id,
            rating: $request->rating,
            review: $request->review,
        );

        $rating = app(CreateRatingAction::class)->execute($data);

        return response()->json([
            'message' => 'Rating submitted successfully',
            'data' => $rating,
        ]);
    }
}
