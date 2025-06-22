<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetailsForCard extends Model
{

    protected $fillable = [
      "customer_id",
      "username",
      "address_city",
      "address_state",
      "email",
      "postal_code",
      "image",
      "time_created",
    ];
}
