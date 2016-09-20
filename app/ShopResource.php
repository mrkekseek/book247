<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\ShopResourcePrice;

class ShopResource extends Model
{
    protected $table = 'shop_resources';
    protected $primaryKey = 'id';

    public static $attributeNames = array(
        'location_id' => 'Shop Name',
        'name' => 'Resource Name',
        'description' => 'Description',
        'category_id' => 'Category ID',
        'color_code' => 'Color Code',
        'session_price'=>'Default Session Price',
        'vat_id'    => 'Vat Rate'
    );
    public static $validationMessages = array(

    );

    protected $fillable = array(
        'location_id',
        'name',
        'description',
        'category_id',
        'color_code',
        'session_price',
        'vat_id'
    );

    public function prices(){
        return $this->hasMany('App\ShopResourcePrice','resource_id','id');
    }

    public function shop_location(){
        return $this->hasOne('App\ShopLocations', 'id', 'location_id');
    }

    public function category(){
        return $this->hasOne('App\ShopResourceCategory', 'id', 'category_id');
    }

    public function vatRate(){
        return $this->hasOne('App\VatRate', 'id', 'vat_id');
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
                    'location_id'   => 'exists:shop_locations,id',
                    'name'          => 'required|unique:shop_resources,name|min:5|max:75',
                    'category_id'   => 'exists:shop_resource_categories,id',
                    'session_price' => 'numeric',
                    'vat_id'        => 'exists:vat_rates,id',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'location_id'   => 'exists:shop_locations,id',
                    'name'          => 'required|min:5|max:75|unique:shop_resources,name'.($id ? ','.$id.',id' : ''),
                    'category_id'   => 'exists:shop_resource_categories,id',
                    'session_price' => 'numeric',
                    'vat_id'        => 'exists:vat_rates,id',
                ];
            }
            default:break;
        }
    }

    public function get_price($date, $start_time){
        $final_price = $this->session_price;

        if (gettype($date)=="string"){
            $book_day = Carbon::createFromFormat('Y-m-d',$date);
            $week_day = Carbon::createFromFormat('Y-m-d',$date)->format('w');
        }
        else{
            $book_day = $date->format('Y-m-d');
            $week_day = $date->format('w');
        }

        if (strlen($start_time)<=5){
            $book_time= Carbon::createFromFormat('H:s', $start_time);
        }
        else{
            $book_time= Carbon::createFromFormat('H:s:i', $start_time);
        }

        // check specific resource prices if exists
        $all_prices = ShopResourcePrice::where('resource_id','=',$this->id)->orderBy('type','ASC')->get();
        if (sizeof($all_prices)>0){
            foreach($all_prices as $price){
                $in_days    = json_decode($price->days);
                $time_start = Carbon::createFromFormat('H:i:s', $price->time_start);
                $time_stop  = Carbon::createFromFormat('H:i:s', $price->time_stop);

                if ($price->type=='specific'){
                    $date_start = Carbon::createFromFormat('Y-m-d', $price->date_start);
                    $date_stop  = Carbon::createFromFormat('Y-m-d', $price->date_stop);
                    if ($book_day->gte($date_start) && $book_day->lte($date_stop) && in_array($week_day, $in_days) && $book_time->gte($time_start) && $book_time->lt($time_stop)){
                        $final_price = $price->price;
                    }
                }
                else{
                    if (in_array($week_day, $in_days) && $book_time->gte($time_start) && $book_time->lt($time_stop)){
                        $final_price = $price->price;
                    }
                }
            }
        }

        return $final_price;
    }
}
