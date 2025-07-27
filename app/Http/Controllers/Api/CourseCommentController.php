<?php

namespace App\Http\Controllers\Api;


use App\Domains\Interactions\Actions\CreateCommentAction;
use App\Domains\Interactions\DTOs\CommentDTO;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseCommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'comment' => 'required|string|max:1000',
        ]);

        $data = new CommentDTO(
            userId: Auth::id(),
            courseId: $request->course_id,
            comment: $request->comment,
        );

        $comment = app(CreateCommentAction::class)->execute($data);

        return response()->json([
            'message' => 'Comment added successfully',
            'data' => $comment,
        ]);
    }
}
