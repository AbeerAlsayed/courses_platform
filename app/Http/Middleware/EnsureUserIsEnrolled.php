<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsEnrolled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        $section = $request->route('section'); // افتراضًا أن section موجود في المسار
        $course = $section->course;

        if (!$user || !$course->students()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Access denied. Not enrolled.'], 403);
        }

        return $next($request);
    }

}
