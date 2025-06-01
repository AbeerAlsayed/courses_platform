<?php

namespace App\Domains\Auth\Models;

use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id', 'birth_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
