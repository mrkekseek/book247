<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ShopLocations extends Model
{
    protected $table = 'shop_locations';
    protected $primaryKey = 'id';

    public static $attributeNames = array(
        'name' => 'Shop Name',
        'bank_acc_no' => 'Bank Account Number',
        'phone' => 'Phone Number',
        'fax' => 'Fax Number',
        'email' => 'Contact Email',
        'registered_no' => 'Registered Number',
        'visibility' => 'Location Visibility'
    );
    public static $validationMessages = array(
        'registered_no.unique' => 'Duplicate registration number in the database',
    );

    protected $fillable = array(
        'name',
        'address_id',
        'bank_acc_no',
        'phone',
        'fax',
        'email',
        'registered_no',
        'visibility'
    );

    public function address(){
        return $this->hasOne('App\Address');
    }

    public function opening_hours(){
        return $this->hasMany('App\ShopOpeningHour', 'shop_location_id', 'id');
    }

    public function resources(){
        return $this->hasMany('App\ShopResource', 'location_id', 'id');
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
                    'name'  => 'required|min:5|max:50|unique:shop_locations',
                    'bank_acc_no'   => 'required|min:5',
                    'phone' => 'required|min:5',
                    'fax'   => 'required|min:5',
                    'email' => 'required|email:true',
                    'registered_no' => 'required|min:5|unique:shop_locations',
                    'visibility'    => 'in:warehouse,public,pending,suspended',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name'  => 'required|min:5|max:50|unique:shop_locations,name'.($id ? ','.$id.',id' : ''),
                    'bank_acc_no'   => 'required|min:5',
                    'phone' => 'required|min:5',
                    'fax'   => 'required|min:5',
                    'email' => 'required|email:true',
                    'registered_no' => 'required|min:5|unique:shop_locations,registered_no'.($id ? ','.$id.',id' : ''),
                    'visibility'    => 'in:warehouse,public,pending,suspended',
                ];
            }
            default:break;
        }
    }

    public function get_opening_hours($date){
        $hours = [
            'open_at'   => '06:30',
            'close_at'  => '23:00'
        ];

        $book_day = Carbon::createFromFormat('Y-m-d',$date);
        $week_day = Carbon::createFromFormat('Y-m-d',$date)->format('w');

        $allOpeningHours = ShopOpeningHour::where('shop_location_id','=',$this->id)
            ->orderByRaw('FIELD(type, "close_hours", "break_hours", "open_hours")')
            ->orderBy('date_start', 'DESC')->get();
        //xdebug_var_dump($allOpeningHours); exit;
        foreach ($allOpeningHours as $singleHour){
            $days = json_decode($singleHour->days);

            switch ($singleHour->type) {
                case 'open_hours'  :
                    $openAt     = Carbon::createFromFormat('H:i:s', $singleHour->time_start)->format('H:i');
                    $closeAt    = Carbon::createFromFormat('H:i:s', $singleHour->time_stop)->format('H:i');

                    if (is_null($singleHour->date_start) || is_null($singleHour->date_stop)){
                        if (in_array($week_day, $days)){
                            return ['open_at' => $openAt, 'close_at' => $closeAt];
                        }
                    }
                    else{
                        $date_start = Carbon::createFromFormat('Y-m-d', $singleHour->date_start);
                        $date_end   = Carbon::createFromFormat('Y-m-d', $singleHour->date_stop);
                        if ($book_day->gte($date_start) && $book_day->lte($date_end) && in_array($week_day, $days)){
                            return ['open_at' => $openAt, 'close_at' => $closeAt];
                        }
                    }
                    break;
                case 'close_hours' :
                    if (is_null($singleHour->date_start) || is_null($singleHour->date_stop)){
                        // one of the dates is undefined, so we treat it as if it has no dates added and is a generic entry
                        if (in_array($week_day, $days)){
                            return ['open_at' => '00:00', 'close_at' => '00:00'];
                        }
                    }
                    else{
                        // both dates defined
                        $date_start = Carbon::createFromFormat('Y-m-d', $singleHour->date_start);
                        $date_end   = Carbon::createFromFormat('Y-m-d', $singleHour->date_stop);
                        if ($book_day->gte($date_start) && $book_day->lte($date_end) && in_array($week_day, $days)){
                            return ['open_at' => '00:00', 'close_at' => '00:00'];
                        }
                    }
                    break;
                case 'break_hours' : ;
                    break;
            }
        }

        return $hours;
    }
}
