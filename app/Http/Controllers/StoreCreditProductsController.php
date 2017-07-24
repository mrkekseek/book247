<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StoreCreditProducts;
use Auth;
use App\Http\Requests;
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

        $store_credit_products = [];
        foreach(StoreCreditProducts::where("status", "!=", "deleted")->get() as $row)
        {
            $row->discount =  ! empty($row->store_credit_discount_fixed) ? $row->store_credit_discount_fixed : $row->store_credit_discount_percentage . '%';
            $store_credit_products[] = $row;
        }

        return view('admin/store_credit/all', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'all_store_credit' => $store_credit_products
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
            'status' 		   => ['active', 'pending', 'suspended']
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
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user()) 
        {
            return redirect()->intended(route('admin/login'));
        }


        $product = StoreCreditProducts::where('id', '=', $id)->get()->first();
        if ( ! $product)
        {
            $product = false;
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];

        $text_parts  = [
            'title'     => 'View Store Credit Product - ' . $product->name,
            'subtitle'  => '',
            'table_head_text1' => 'Backend Roles Permissions List'
        ];

        $sidebar_link= 'admin-backend-store_credit_product-view_product';

        return view('admin/store_credit/view', [ 
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'product'       => $product,
            'status'        => ['active', 'pending', 'suspended', 'deleted']
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user()) 
        {
            return redirect()->intended(route('admin/login'));
        }

        $product = StoreCreditProducts::where('id', '=', $id)->get()->first();
        if ( ! $product)
        {
            $product = false;
        }

        StoreCreditProducts::where('id', '=', $id)->update(['status' => 'pending']);

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];

        $text_parts  = [
            'title'     => 'Edit Store Credit Product - ' . $product->name,
            'subtitle'  => '',
            'table_head_text1' => 'Backend Roles Permissions List'
        ];

        $sidebar_link= 'admin-backend-store_credit_product-edit_product';

        return view('admin/store_credit/edit', [ 
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'product'       => $product,
            'status'        => ['active', 'pending', 'suspended', 'deleted']
        ]);
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
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user())
        {
            return [
                'success'   => false,
                'errors'    => 'Error while trying to authenticate. Login first then use this function.',
                'title'     => 'Not logged in'];
        }

        $product = StoreCreditProducts::where('id', '=', $id)->get()->first();

        if ( ! $product)
        {
            return [
                'success' => false,
                'errors'  => 'Store credit product not found in the database',
                'title'   => 'Error updating store credit product'
            ];
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

        $validator = Validator::make($fillable, StoreCreditProducts::rules('PATCH', $product->id), StoreCreditProducts::$message, StoreCreditProducts::$attributeNames);
        if ($validator->fails())
        {
            return array(
                'success' => false,
                'title'  => 'Error validating input information',
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        if($product->update($fillable))
        {
             Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'store_credit_products',
                'action'        => 'Store Credit Update',
                'description'   => 'Store Credit Updated, plan ID : '.$product->id,
                'details'       => 'Store Credit by user : '.$user->id,
                'updated'       => false,
            ]);

            return [
                'success' => true,
                'message' => 'Page will reload so you can add all the other details for this plan...',
                'title'   => 'Store Credit Updated',
                'redirect_link' => route('admin.store_credit_products.index')
            ];
        }

        Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'store_credit_products',
                'action'        => 'Store Credit Update',
                'description'   => 'Store Credit Updated, plan ID : '.$product->id,
                'details'       => 'Store Credit by user : '.$user->id,
                'updated'       => false,
            ]);

            return [
                'success' => false,
                'message' => 'Some e',
                'title'   => 'Store Credit Errors'
            ];
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

    public function store_credit_change_status(Request $request)
    {
         $user = Auth::user();
        if ( ! $user || ! $user->is_back_user())
        {
            return [
                'success'   => false,
                'errors'    => 'Error while trying to authenticate. Login first then use this function.',
                'title'     => 'Not logged in'];
        }

        $vars = $request->only('id', 'status');

        $product = StoreCreditProducts::where('id', '=', $vars["id"])->get()->first();

        if ( ! $product)
        {
            return [
                'success' => false,
                'errors'  => 'Store credit product not found in the database',
                'title'   => 'Error updating store credit product'
            ];
        }

        $fillable = [
            'status' => $vars['status']
        ];

        if($product->update($fillable))
        {
            Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'store_credit_products',
                'action'        => 'Store Credit Change Status',
                'description'   => 'Store Credit Change Status, plan ID : '.$product->id,
                'details'       => 'Store Credit by user : '.$user->id,
                'updated'       => false,
            ]);

            return [
                'success' => true,
                'message' => 'Page will reload so you can add all the other details for this plan...',
                'title'   => 'Store Credit Change Status'
            ];
        }

        Activity::log([
            'contentId'     => $user->id,
            'contentType'   => 'store_credit_products',
            'action'        => 'Store Credit Not Change Status',
            'description'   => 'Store Credit Not Change Status, plan ID : '.$product->id,
            'details'       => 'Store Credit by user : '.$user->id,
            'updated'       => false,
        ]);

        return [
            'success' => false,
            'message' => 'Page will reload so you can add all the other details for this plan...',
            'title'   => 'Store Credit Error Change Status'
        ];
    }
}
