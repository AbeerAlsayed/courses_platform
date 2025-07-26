<?php

namespace App\Domains\Payments\Exceptions;

use App\Domains\Courses\Models\Course;
use Exception;
use Illuminate\Support\Facades\Log;

class InvalidCoursePriceException extends Exception
{
    private ?Course $course;

    public function __construct(?Course $course = null, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $this->course = $course;

        $defaultMessage = $this->course
            ? sprintf('Invalid price (%s) for course: %s', $this->course->price, $this->course->title)
            : 'Course price is invalid';

        parent::__construct($message ?: $defaultMessage, $code, $previous);
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $this->getMessage(),
                'course_id' => $this->course?->id,
            ], 422);
        }

        return redirect()
            ->back()
            ->withInput()
            ->withErrors(['price' => $this->getMessage()]);
    }

    public function report()
    {
        Log::error($this->getMessage(), [
            'course_id' => $this->course?->id,
            'price' => $this->course?->price,
            'exception' => $this
        ]);
    }
}
