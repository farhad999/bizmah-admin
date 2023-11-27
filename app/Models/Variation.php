<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
  use HasFactory;

  protected $guarded = ['id'];
  protected $appends = ['image_url'];
  protected $casts = [
    'price' => 'double',
    'old_price' => 'double'
  ];

  public function getImageUrlAttribute()
  {

    if (!empty($this->image)) {
      return asset('storage/' . $this->image);
    }

    return asset('assets/img/no-image.png');
  }

}
