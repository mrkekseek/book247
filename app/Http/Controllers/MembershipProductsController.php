<?php

namespace App\Http\Controllers;

use App\MembershipProduct;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Regulus\ActivityLog\Models\Activity;

class MembershipProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $all_products = [];
        $membership_products = Cache::remember('membership_products_table',720,function(){
            return MembershipProduct::orderBy('name','asc')->get();
        });

        if ($membership_products){
            $i = 1;
            foreach($membership_products as $single){
                $all_products[$i] = $single;
                $i++;
            }
        }

        $breadcrumbs = [
            'Home'                  => route('admin'),
            'Administration'        => route('admin'),
            'Membership Products'   => '',
        ];
        $text_parts  = [
            'title'     => 'Membership Products List',
            'subtitle'  => 'add/edit/view membership products',
            'table_head_text1' => ''
        ];
        $sidebar_link= 'admin-backend-membership_products-list_all';

        return view('admin/membership_products/all_products', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'all_products'=> $all_products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }
        elseif (!$user->can('create-calendar-products')){
            return redirect()->intended(route('admin/error/permission_denied'));
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];
        $text_parts  = [
            'title'     => 'Add new membership plan',
            'subtitle'  => '',
            'table_head_text1' => 'Membership Plans - Create New'
        ];
        $sidebar_link = 'admin-backend-membership_products-new_product';

        return view('admin/membership_products/add_product', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
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
        if (!$user || !$user->is_back_user()) {
            //return redirect()->intended(route('admin/login'));
            return [
                'success' => false,
                'errors' => 'Error while trying to authenticate. Login first then use this function.',
                'title' => 'Not logged in'];
        }
        elseif (!$user->can('manage-calendar-products')){
            return [
                'success'   => false,
                'errors'    => 'You don\'t have permission to access this page',
                'title'     => 'Permission Error'];
            //return redirect()->intended(route('admin/error/permission_denied'));
        }

        $vars = $request->only('name', 'color_code');
        $fillable = [
            'name'          => $vars['name'],
            'color_code'    => $vars['color_code'],
        ];
        $validator = Validator::make($fillable, MembershipProduct::rules('POST'), MembershipProduct::$validationMessages, MembershipProduct::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'title'  => 'Error validating input information',
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        Cache::forget('membership_products_table');
        $the_product = MembershipProduct::create($fillable);

        if ($the_product){
            Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'membership_product',
                'action'        => 'New Membership Product',
                'description'   => 'New membership product created : '.$the_product->id,
                'details'       => 'Created by user : '.$user->id,
                'updated'       => false,
            ]);

            return [
                'success' => true,
                'message' => 'Page will reload so you can add more membership type products...',
                'title'   => 'Membership Product Created',
            ];
        }
        else{
            return [
                'success'   => false,
                'title'     => 'Error validating input information',
                'errors'    => ''
            ];
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
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $the_product = MembershipProduct::where('id','=',$id)->get()->first();
        if (!$the_product){
            $the_product = false;
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];
        $text_parts  = [
            'title'     => 'Edit Plane - Full month free',
            'subtitle'  => '',
            'table_head_text1' => 'Backend Roles Permissions List'
        ];
        $sidebar_link= 'admin-backend-membership_products-show_product';

        return view('admin/membership_products/view_product', [
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'membership_product'   => $the_product,
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
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }
        elseif (!$user->can('manage-calendar-products')){
            /*return [
                'success'   => false,
                'errors'    => 'You don\'t have permission to access this page',
                'title'     => 'Permission Error'];*/
            return redirect()->intended(route('admin/error/permission_denied'));
        }

        $the_product = MembershipProduct::where('id','=',$id)->get()->first();
        if (!$the_product){
            $the_product = false;
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];
        $text_parts  = [
            'title'     => 'Edit Membership - '.$the_product->name,
            'subtitle'  => '',
            'table_head_text1' => 'Backend Roles Permissions List'
        ];
        $sidebar_link= 'admin-backend-membership_products-edit_product';

        return view('admin/membership_products/edit_product', [
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'membership_product'    => $the_product,
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
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'errors'  => 'Error while trying to authenticate. Login first then use this function.',
                'title'   => 'Not logged in'];
        }
        elseif (!$user->can('manage-calendar-products')){
            /*return [
                'success'   => false,
                'errors'    => 'You don\'t have permission to access this page',
                'title'     => 'Permission Error'];*/
            return redirect()->intended(route('admin/error/permission_denied'));
        }

        $the_product = MembershipProduct::where('id', '=', $id)->get()->first();
        if (!$the_product){
            return [
                'success' => false,
                'errors'  => 'Membership product not found in the database',
                'title'   => 'Error updating membership'
            ];
        }

        $vars = $request->only('name','color_code');
        $fillable = [
            'name'      => $vars['name'],
            'color_code'=> $vars['color_code'],
        ];
        $validator = Validator::make($fillable, MembershipProduct::rules('PATCH', $the_product->id), MembershipProduct::$validationMessages, MembershipProduct::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'title'  => 'Error validating input information',
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        else {
            Cache::forget('membership_products_table');
            $the_product->update($fillable);
        }

        Activity::log([
            'contentId'     => $user->id,
            'contentType'   => 'membership_product',
            'action'        => 'Membership Product Update',
            'description'   => 'Membership Product Updated, product ID : '.$the_product->id,
            'details'       => 'Updated by user : '.$user->id,
            'updated'       => false,
        ]);

        return [
            'success' => true,
            'message' => 'Page will reload so you can make other changes if necessary',
            'title'   => 'Membership Product Updated',
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
        //
        Cache::forget('membership_products_table');
    }
}