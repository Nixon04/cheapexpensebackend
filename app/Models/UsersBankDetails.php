<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersBankDetails extends Model
{
    use HasFactory;
    protected $fillable = [
      "username",
      "bank_name_id",
      "user_account",
      "bank_user_name",
      "user_bank"
    ];
}
