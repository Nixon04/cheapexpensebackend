<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllDataPackage extends Model
{
    //
    protected $fillable = [
        'plan_id',
        'title',
        'amount',
        'network_type',
        'alias',
        'reference',
        'status',
    ];
}
