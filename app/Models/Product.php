<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
  use HasFactory, Sluggable;

  protected $guarded = ['id'];
  protected $appends = ['image_url', 'secondary_image_url'];

  function sluggable(): array
  {
    // TODO: Implement sluggable() method.
    return [
      'slug' => [
        'source' => 'name',
        'onUpdate' => true,
      ]
    ];
  }

  static function findBySlug($slug){
    return self::where('slug', $slug)->first();
  }

  function getImageUrlAttribute()
  {
    if (!$this->image) {
      return asset( '/assets/img/no-image.png');
    }
    return Storage::url($this->image);
  }

  function getSecondaryImageUrlAttribute(){
    if (!$this->secondary_image) {
      return asset( '/assets/img/no-image.png');
    }
    return Storage::url($this->secondary_image);
  }

  function category()
  {
    return $this->belongsTo(Category::class, 'category_id');
  }

  function subCategory()
  {
    return $this->belongsTo(Category::class, 'sub_category_id');
  }

  function subSubCategory()
  {
    return $this->belongsTo(Category::class, 'sub_sub_category_id');
  }

  function brand()
  {
    return $this->belongsTo(Brand::class, 'brand_id');
  }

  function variations()
  {
    return $this->hasMany(Variation::class, 'product_id');
  }

  function images(){
    return $this->hasMany(ProductImage::class, 'product_id');
  }

}
