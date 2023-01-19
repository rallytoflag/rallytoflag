<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['title', 'description', 'location', 'start_date', 'end_date', 'start_time', 'url', 'user_id', 'longitude', 'latitude'];

    public function user() {
      return $this->belongsTo(User::class);
    }

    public function getFirstImage() {
      return $this->getMedia('images')->first();
    }

    public function getImages() {
      return $this->getMedia('images')->map(function ($image) {
        return $image->getUrl();
      });
    }
}
