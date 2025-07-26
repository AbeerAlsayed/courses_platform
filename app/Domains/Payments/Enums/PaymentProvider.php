<?php
namespace App\Domains\Payments\Enums;

enum PaymentProvider: string
{
    case STRIPE = 'stripe';
    // يمكن إضافة مزودين آخرين لاحقاً
}
