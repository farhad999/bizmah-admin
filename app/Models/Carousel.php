<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Carousel extends Model
{
  use HasFactory;

  protected $guarded = ['id'];

  function slides()
  {
    return $this->hasMany(CarouselSlide::class, 'carousel_id');
  }


}
