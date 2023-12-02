<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CarouselSlide extends Model
{
  use HasFactory;

  protected $guarded = ['id'];

  protected $appends = ['image_url'];

  //append image url
  public function getImageUrlAttribute()
  {
    return Storage::url($this->image);
  }

}
