<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSignup extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'username',
        'email',
        'contact',
        'password',
        'profileimage',
        'resetcode',
        'dob',
        'date',
        'users_id',
        'referral_id'
    ];
}
