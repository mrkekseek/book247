<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StoreCreditProducts;
use App\Http\Requests;
use Auth;
use Validator;
use Activity;

class StoreCreditProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];

        $text_parts  = [
            'title'     => 'All Store Credits Products',
            'subtitle'  => 'add/edit/view plans',
            'table_head_text1' => 'Backend Roles Permissions List'
        ];

        $sidebar_link = 'admin-backend-all-packs';

        return view('admin/store_credit/all', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'all_store_credit' => StoreCreditProducts::with("users")->get()
        ]);
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];

        $text_parts  = [
            'title'     => 'Add Store Credit Plans',
            'subtitle'  => 'add/edit/view plans',
            'table_head_text1' => 'Backend Roles Permissions List'
        ];

        $sidebar_link= 'admin-backend-add-packs';

        return view('admin/store_credit/add', [
            'breadcrumbs' 	   => $breadcrumbs,
            'text_parts' 	   => $text_parts,
            'in_sidebar'  	   => $sidebar_link,
            'status' 		   => ['active', 'pending', 'suspended', 'deleted']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$user = Auth::user();
        if ( ! $user || ! $user->is_back_user())
        {
            return [
                'success' => false,
                'errors' => 'Error while trying to authenticate. Login first then use this function.',
                'title' => 'Not logged in'];
        }

        $vars = $request->only('name', 'description', 'store_credit_value', 'store_credit_price', 'store_credit_discount_fixed', 'store_credit_discount_percentage', 'validity_days', 'valid_from', 'valid_to', 'packages_per_user', 'status');

        $fillable = [
            'name' => $vars['name'],
            'description' => $vars['description'],
            'store_credit_value' => $vars['store_credit_value'],
            'store_credit_price' => $vars['store_credit_price'],
            'store_credit_discount_fixed' => $vars['store_credit_discount_fixed'],
            'store_credit_discount_percentage' => empty($vars['store_credit_discount_fixed']) ? $vars['store_credit_discount_percentage'] : 0,
            'validity_days' => $vars['validity_days'],
            'valid_from' => $vars['valid_from'],
            'valid_to' => $vars['valid_to'],
            'packages_per_user' => $vars['packages_per_user'],
            'status' => $vars['status'],
            'added_by' => $user->id
        ];

        $validator = Validator::make($fillable, StoreCreditProducts::rules('POST'), StoreCreditProducts::$message, StoreCreditProducts::$attributeNames);
        if ($validator->fails())
        {
            return array(
                'success' => false,
                'title'  => 'Error validating input information',
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {

            $plan = StoreCreditProducts::create($fillable);

            Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'store_credit_products',
                'action'        => 'New Store Credit Products',
                'description'   => 'New store credit products created : ' . $plan->id,
                'details'       => 'Created by user : ' . $user->id,
                'updated'       => false,
            ]);

            return [
                'success' 		=> true,
                'message'		=> 'Page will reload so you can add all the other details for this plan...',
                'title'   		=> 'Membership Plan Created'
            ];
        }
        catch (Exception $e)
        {
            return Response::json(['error' => 'Booking Error'], Response::HTTP_CONFLICT);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
