<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClearCurrentIdNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        "username",
        "notification_id",
        "status"
    ];
}
