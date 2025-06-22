<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualCardList extends Model
{
    //

    protected $fillable = [
        'transaction_id',
        'username',
        'customer_id',
        'customer_name',
        'card_id',
        'card_type',
        'currency',
        'brand',
        'name',
        'first_six',
        'last_six',
        'masked',
        'frontnumber',
        'expiry',
        'cvv',
        'street',
        'city',
        'state',
        'country',
        'postal_code',
    ];
}
