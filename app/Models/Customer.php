<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
  use HasFactory, HasApiTokens, SoftDeletes;

  protected $guarded = ['id'];

  function addresses()
  {
    return $this->hasMany(Address::class, 'customer_id');
  }

  function carts(){
    return $this->hasMany(Cart::class, 'customer_id');
  }

}
