<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBodyPackage extends Model
{
    use HasFactory;
    protected $fillable = [
     'packagename',
     'packagesubname',
     'packagereference'
    ];
}
