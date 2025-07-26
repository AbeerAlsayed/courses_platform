<?php

namespace App\Exceptions;

use App\Domains\Cart\Exceptions\CourseAlreadyInCartException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [
        //
    ];

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (UnauthorizedException $e, $request) {
            throw new UnauthorizedRoleException();
        });

        $this->renderable(function (CourseAlreadyInCartException $e, $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 409);
            }

            // للطلبات العادية (html) يمكن عرض صفحة خطأ أو رسالة نصية
            return response($e->getMessage(), 409);
        });
    }

}
