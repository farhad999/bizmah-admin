<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Brand extends Model
{
  use HasFactory, SoftDeletes;

  protected $guarded = ['id'];

  use Sluggable;

  protected $appends = ['image_url'];

  public function sluggable(): array
  {
    return [
      'slug' => [
        'source' => 'name'
      ]
    ];
  }

  function getImageUrlAttribute()
  {
    if (!$this->image) {
      return null;
    }

    return Storage::url($this->image);
  }

  static function getForDropdown()
  {
    return self::where('status', 1)->pluck('name', 'id');
  }

}
