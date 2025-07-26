<?php

namespace App\Support;

use NumberFormatter;

class MoneyFormatter
{
    public static function format($amount, ?string $currency = null): string
    {
        $currency = $currency ?: config('services.stripe.currency');

        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($amount, $currency);
    }
}
