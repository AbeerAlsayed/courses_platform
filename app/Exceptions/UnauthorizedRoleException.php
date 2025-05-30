<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedRoleException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'status' => false,
            'message' => 'You are not authorized to perform this action.',
        ], 403);
    }
}
