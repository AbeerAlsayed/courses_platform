<?php

namespace App\Http\Controllers\Api;

use App\Domains\Interactions\DTOs\QuestionDTO;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Interactions\Actions\CreateQuestionAction;
use Illuminate\Support\Facades\Auth;

class CourseQuestionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'question' => 'required|string|max:1000',
        ]);

        $data = new QuestionDTO(
            userId: Auth::id(),
            courseId: $request->course_id,
            question: $request->question,
        );

        $question = app(CreateQuestionAction::class)->execute($data);

        return response()->json([
            'message' => 'Question posted successfully',
            'data' => $question,
        ]);
    }
}
