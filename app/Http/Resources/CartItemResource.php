<?php
// app/Http/Resources/CartItemResource.php

namespace App\Http\Resources;

use App\Support\MoneyFormatter;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => MoneyFormatter::format($this->price), // أو 'SAR'
            'added_at' => now()->toDateTimeString(),
        ];
    }
}
