<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PinAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'attempt',
        'last_attempt_at',
    ];
}
