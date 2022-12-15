<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'location', 'start_date', 'end_date', 'url', 'user_id'];

    public function images() {
      return $this->hasMany(EventImage::class);
    }
}
