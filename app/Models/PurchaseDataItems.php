<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDataItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'networkId',
        'networkName',
        'networkCode',
        'networkPrice',
        'networkPlansList',
        'networkPackageSpace',
    ];
}
