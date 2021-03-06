<?php

namespace App;

use App\Http\Controllers\BookingController;
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
        return $this->hasOne('App\Address','id','address_id');
    }

    public function opening_hours(){
        return $this->hasMany('App\ShopOpeningHour', 'shop_location_id', 'id');
    }

    public function resources(){
        return $this->hasMany('App\ShopResource', 'location_id', 'id');
    }

    public function systemOptions(){
        return $this->hasMany('App\ShopSystemOption','shop_location_id','id');
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
                    'bank_acc_no'   => '',
                    'phone' => 'required|min:5',
                    'fax'   => 'required|min:5',
                    'email' => 'required|email:true',
                    'registered_no' => '',
                    'visibility'    => 'in:warehouse,public,pending,suspended',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name'  => 'required|min:5|max:50|unique:shop_locations,name'.($id ? ','.$id.',id' : ''),
                    'bank_acc_no'   => '',
                    'phone' => 'required|min:5',
                    'fax'   => 'required|min:5',
                    'email' => 'required|email:true',
                    'registered_no' => '',
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

    public function set_system_option($key, $value){
        try {
            $fillable = [
                'shop_location_id'  => $this->id,
                'var_name'          => $key,
            ];
            $systemSetting = ShopSystemOption::firstOrNew($fillable);
            $systemSetting->var_value = $value;
            $systemSetting->save();

            return true;
        }
        catch (\Exception $ex){
            return false;
        }
    }

    public function get_system_option($key){
        $setting = ShopSystemOption::where('shop_location_id','=',$this->id)->where('var_name','=',$key)->get()->first();
        if ($setting){
            return $setting->var_value;
        }
        else{
            return false;
        }
    }

    public function set_financial_profile($profile_id){
        $financialProfile = FinancialProfile::where('id','=',$profile_id)->get()->first();
        if (!$financialProfile){
            if ($profile_id==-1){
                $shopFinancialProfile = ShopFinancialProfile::where('shop_location_id','=',$this->id)->get()->first();
                if ($shopFinancialProfile){
                    $shopFinancialProfile->delete();
                }

                return true;
            }
            else{
                return false;
            }
        }
        else{
            $shopFinanceProfile = ShopFinancialProfile::firstOrCreate(['shop_location_id'=>$this->id]);
            $shopFinanceProfile->shop_location_id = $this->id;
            $shopFinanceProfile->financial_profile_id = $financialProfile->id;
            $shopFinanceProfile->save();

            return true;
        }
    }

    public function get_financial_profile(){
        $shopFinancialProfile = ShopFinancialProfile::where('shop_location_id','=',$this->id)->get()->first();
        if ($shopFinancialProfile){
            return $shopFinancialProfile->financial_profile_id;
        }
        else{
            return -1;
        }
    }

    //
    public function get_location_available_hours($date_selected, $fullHours=false){
        $locationOpeningHours = $this->get_opening_hours($date_selected);

        if (isset($locationOpeningHours['open_at']) && isset($locationOpeningHours['close_at'])){
            $open_at  = $locationOpeningHours['open_at'];
            $close_at = $locationOpeningHours['close_at'];
        }
        else{
            $open_at  = '07:00';
            $close_at = '23:00';
        }

        $hours_interval = BookingController::make_hours_interval($date_selected, $open_at, $close_at, 30, $fullHours, false);

        return $hours_interval;
    }
}
