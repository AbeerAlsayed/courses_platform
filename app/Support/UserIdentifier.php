<?php

namespace App\Support;

class UserIdentifier
{
    public static function get(): int|string
    {
        return auth()->check() ? auth()->id() : session()->getId();
    }
}
