<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllServices extends Model
{
    use HasFactory;

    protected $fillable = [
        "service_name",
        "status"
    ];
}
