<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualAccounts extends Model
{
    use HasFactory;

    protected $fillable = [
      "username",
      "account_name",
      "account_number",
      "current_bank",
      "date",
    ];
}
