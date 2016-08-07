<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ShopResourceController extends Controller
{
    /** $id = the id of the shop resource */
    public function get_details($id){

    }

    public function get_rooms_for_activity(Request $request){
        $vars = $request->only('selected_category');
        $rooms = [];

        $locations = DB::table('shop_resources')
            ->select('location_id', DB::raw('count(*) as total'))
            ->where('category_id','=',$vars['selected_category'])
            ->groupBy('location_id')
            ->get();
        if ($locations){
            foreach($locations as $loc){
                $rooms[$loc->location_id] = $loc->total ;
            }
        }

        return [
            'success'   => true,
            'rooms'     => $rooms
        ];
    }
}
