<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EligibleForCard extends Model
{
    //
    protected $fillable = [
        'username',
        "bvn",
        "nin",
        "revoke",
    ];
}
