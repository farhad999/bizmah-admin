<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
  use HasFactory;

  static function getForDropdown()
  {
    return static::orderBy('name')
      ->pluck('name', 'name');
  }

  function zones()
  {
    return $this->hasMany(Zone::class, 'city_id');
  }

}
