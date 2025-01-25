<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccountDetails extends Model
{
    use HasFactory;

    protected $fillable = [
     'username',
     'user_ref_id',
     'user_amount',
     'user_bonus',
     'last_update',
     'withdrawer_count'
    ];

}
