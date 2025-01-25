<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdsShow extends Model
{
    use HasFactory;
    protected $fillable = [
        "ad_id",
        "title",
        "title_frame",
        "image",
        "message",
    ];

}
