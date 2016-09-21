<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopResourcePrice extends Model
{
    protected $table = 'shop_resource_prices';
    protected $primaryKey = 'id';

    public static $attributeNames = [
        'resource_id'   => 'Resource ID',
        'days'          => 'Included days',
        'time_start'    => 'Time Start',
        'time_stop'     => 'Time Stop',
        'day_start' => 'Day Start',
        'day_stop'  => 'Dai stop',
        'type'      => 'Price type - general/specific',
        'group_price'   => 'Group Price',
        'price'     => 'Price',
        'vat_id'    => 'VAT ID'     ];

    public static $validationMessages = [];

    protected $fillable = [
        'resource_id',
        'days',
        'time_start',
        'time_stop',
        'date_start',
        'date_stop',
        'type',
        'group_price',
        'price',
        'vat_id'    ];

    public function shop_resource(){
        return $this->belongsTo('App\ShopResource', 'id', 'resource_id');
    }

    public static function rules($method, $id=0){
        switch($method){
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'resource_id'   => 'exists:shop_resources,id',
                    'days'          => 'required|min:1|max:50',
                    'time_start'    => 'required|date_format:"H:i:s"',
                    'time_stop'     => 'required|date_format:"H:i:s"',
                    'date_start'    => 'date',
                    'date_stop'     => 'date',
                    'type'          => 'required|in:general,specific',
                    'price'         => 'required|numeric',
                    'vat_id'        => 'required|exists:vat_rates,id',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'resource_id'   => 'exists:shop_resources,id',
                    'days'          => 'required|min:1|max:50',
                    'time_start'    => 'required|date_format:"H:i:s"',
                    'time_stop'     => 'required|date_format:"H:i:s"',
                    'date_start'    => 'date',
                    'date_stop'     => 'date',
                    'type'          => 'required|in:general,specific',
                    'price'         => 'required|numeric',
                    'vat_id'        => 'required|exists:vat_rates,id',
                ];
            }
            default:break;
        }
    }
}
