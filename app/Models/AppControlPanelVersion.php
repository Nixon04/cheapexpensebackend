<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppControlPanelVersion extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "version",
        "status",
        "statement_approved",
        "last_updated",
        
    ];
}
