<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product_type extends Model
{
    protected $fillable = ['business_id', 'product_type', 'description'];
}
