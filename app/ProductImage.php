<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'product_images';
    protected $primaryKey = 'id';

    protected $fillable = array(
        'product_id',
        'label',
        'file_location',
        'image_size',
        'sort_order',
    );

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
