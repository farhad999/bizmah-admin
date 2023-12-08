<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
  use HasFactory, SoftDeletes, Sluggable;

  protected $guarded = ['id'];
  protected $appends = ['image_url', 'banner_image_url'];

  function children()
  {
    return $this->hasMany(Category::class, 'parent_id');
  }

  function sluggable(): array
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

  function getBannerImageUrlAttribute()
  {
    if (!$this->banner_image) {
      return asset('/assets/img/no-image.png');
    }
    return Storage::url($this->banner_image);
  }

  function parent()
  {
    return $this->belongsTo(Category::class, 'parent_id');
  }

  static function getForDropdown($parent = null)
  {
    return self::where('parent_id', $parent)
      ->where('status', 1)
      ->pluck('name', 'id');
  }

}
