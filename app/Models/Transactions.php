<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
         'amount',
         'type_of_purcahse',
         'sub_type_purchase',
         'data_type',
         'status',
         'ref_num_purchase',
         'reference',
         'date_of_purchase',
    ];
}
