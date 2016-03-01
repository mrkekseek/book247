<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public function product_category(){
        return $this->belongsTo('App\ProductCategories', 'id', 'category_id');
    }
}
