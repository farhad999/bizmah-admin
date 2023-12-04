<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    function product(){
      return $this->belongsTo(Product::class, 'product_id');
    }
    function variation(){
      return $this->belongsTo(Variation::class, 'variation_id');
    }

}
