<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationTemplate extends Model
{
  use HasFactory;

  protected $guarded = ['id'];

  static function getFordropdown()
  {
    return self::where('status', 1)
      ->pluck('name', 'id');
  }

}
