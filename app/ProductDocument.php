<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDocument extends Model
{
    protected $table = 'product_documents';
    protected $primaryKey = 'id';

    protected $fillable = array(
        'product_id',
        'file_name',
        'file_location',
        'file_type',
        'label',
        'category',
    );

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
