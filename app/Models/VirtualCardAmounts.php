<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualCardAmounts extends Model
{
    //
    protected $fillable = [
       'username',
       'card_id',
       'amount',
       "flag",
       'card_flag',
       "convert",
       "date"
    ];
}
