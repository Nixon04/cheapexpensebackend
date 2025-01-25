<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsFeedModel extends Model
{
    use HasFactory;
    protected $fillable = [
       "news_ref_id",
       "news_title",
       "news_image",
       "news_message",
       "last_updated",
    ];
}
