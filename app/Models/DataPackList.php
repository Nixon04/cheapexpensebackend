<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPackList extends Model
{
    //

    protected $fillable = [
        'network',
        'plan_code',
        'name',
        'alias',
        'amount',
        'current_amount',
        'duration_type',
    ];
}
