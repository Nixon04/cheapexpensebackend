<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClearNotification extends Model
{
    use HasFactory;
     protected $fillable = [
      "username",
      "status"
     ];
}
