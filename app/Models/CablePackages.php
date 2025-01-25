<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CablePackages extends Model
{
    use HasFactory;

    protected $fillable = [
        "packagename",
        "variation_name",
        "variation_code",
        "variation_amount",
        "fixed_price",
        "calculated_price",
    ];
}
