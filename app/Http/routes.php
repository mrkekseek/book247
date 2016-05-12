<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
    // default login/register/forgot password routes
    Route::auth();

    Route::get('/', [
        'as'    => 'homepage',
        'uses'  => 'FrontPageController@index'
    ]);

    Route::post('login',[
        'as'    => 'front_login',
        'uses'  => 'FrontPageController@authenticate'
    ]);

    Route::get('login-new', function(){
        return view('login');
    });

    Route::get('admin/login', [
        'as' => 'admin/login',
        function(){
            return view('admin/auth/login');
        }]);

    Route::post('admin/login', [
        'as'    => 'admin/login',
        'uses'  => 'AdminController@authenticate']
    );

    Route::get('admin/logout', [
        'as'    => 'admin/logout',
        'uses'  => 'AdminController@logout'
    ]);

    //Route::get('/admin/text-auth', 'HomeController@index');

    Route::get('/admin', [
        'as'    => 'admin',
        'uses'  => 'AdminController@index'
    ]);

    /** Start - Back end users route */
    Route::get('/admin/back_users/', [
        'as'    =>  'admin/back_users',
        'uses'  =>  'BackEndUserController@index'
    ]);

    Route::put('/admin/back_users/add_user', [
        'as'    =>  'admin/back_users/add_user',
        'uses'  =>  'BackEndUserController@create'
    ]);

    Route::get('/admin/back_users/view_user/{id}', [
        'as' => 'admin/back_users/view_user/',
        'uses' => 'BackEndUserController@show',
    ]);

    Route::post('admin/back_users/view_user/{id}/acc_info', [
        'as' => 'admin/back_users/view_user/acc_info',
        'uses' => 'BackEndUserController@update_account_info',
    ]);

    Route::post('admin/back_users/view_user/{id}/personal_info', [
        'as' => 'admin/back_users/view_user/personal_info',
        'uses' => 'BackEndUserController@update_personal_info',
    ]);

    Route::post('admin/back_users/view_user/{id}/password_update', [
        'as' => 'admin/back_users/view_user/password_update',
        'uses' => 'BackEndUserController@updatePassword',
    ]);

    Route::post('admin/back_users/view_user/{id}/personal_address', [
        'as' => 'admin/back_users/view_user/personal_address',
        'uses' => 'BackEndUserController@update_personal_address',
    ]);

    Route::post('admin/back_users/view_user/{id}/avatar_image', [
        'as' => 'admin/back_users/view_user/avatar_image',
        'uses' => 'BackEndUserController@update_personal_avatar',
    ]);

    Route::post('admin/back_users/view_user/{id}/add_document', [
        'as' => 'admin/back_users/view_user/add_document',
        'uses' => 'BackEndUserController@add_account_document',
    ]);

    Route::get('admin/back_users/{id}/get_document/{document_name}',[
        'as' => 'admin/back_user/get_document',
        'uses' => 'BackEndUserController@get_user_account_document'
    ]);
    //** Start - Back end users route */

    Route::post('admin/users/ajax_get_info', [
       'as'     => 'admin/users/ajax_get_info',
        'uses'  => 'BackEndUserController@ajax_get_user_info'
    ]);

    Route::post('admin/users/ajax_get_users', [
        'as'     => 'admin/users/ajax_get_users',
        'uses'  => 'BackEndUserController@ajax_get_users'
    ]);

    Route::post('admin/users/ajax_get_bill_address', [
        'as'     => 'admin/users/ajax_get_bill_address',
        'uses'  => 'BackEndUserController@ajax_get_bill_address'
    ]);

    Route::post('admin/users/ajax_get_ship_address', [
        'as'     => 'admin/users/ajax_get_ship_address',
        'uses'  => 'BackEndUserController@ajax_get_ship_address'
    ]);

    /** Routes for employees and backend users roles */
    Route::get('/admin/back_users/user_roles', [
        'as'    =>  'admin/back_users/user_roles',
        'uses'  =>  'RolesController@all_users_roles'
    ]);

    Route::get('/admin/back_users/roles_permissions', [
        'as'    =>  'admin/back_users/roles_permissions',
        'uses'  =>  'RolesController@list_permissions'
    ]);

    Route::post('/admin/back_users/roles_permissions', [
        'as'    =>  'admin/back_users/roles_permissions',
        'uses'  =>  'RolesController@add_permission'
    ]);

    Route::get('/admin/back_users/roles_permissions/{id}', [
        'as'    =>  'admin/back_users/roles_permissions/view',
        'uses'  =>  'RolesController@view_permission'
    ]);

    Route::put('/admin/back_users/roles_permissions/{id}', [
        'as'    =>  'admin/back_users/roles_permissions/view',
        'uses'  =>  'RolesController@update_permission'
    ]);

    Route::post('/admin/back_users/user_roles', [
        'as'    =>  'admin/back_users/user_roles',
        'uses'  =>  'RolesController@add_user_role'
    ]);

    Route::put('/admin/back_users/user_roles', [
        'as'    =>  'admin/back_users/user_roles',
        'uses'  =>  'RolesController@update_user_role'
    ]);

    Route::delete('/admin/back_users/user_roles', [
        'as'    =>  'admin/back_users/user_roles',
        'uses'  =>  'RolesController@delete_user_role'
    ]);
    /** End Routes for employees roles */

    /** Start Routes for Shops/Locations */
    Route::get('/admin/shops/locations', [
        'as'    => 'admin/shops/locations/all',
        'uses'  => 'ShopController@list_all'
    ]);

    Route::get('/admin/shops/locations/{id}', [
        'as'    => 'admin/shops/locations/view',
        'uses'  => 'ShopController@get_shop_location'
    ]);

    Route::post('/admin/shops/locations/add', [
        'as'    => 'admin/shops/locations/add',
        'uses'  => 'ShopController@add_shop_location'
    ]);

    Route::get('admin/shops/employee_work_plan', [
        'as'    => 'admin/shops/employee_work_plan',
        'uses'  => 'ShopController@shops_employee_working_plan'
    ]);

    Route::post('admin/shops/get_all_locations', [
        'as'    => 'admin/shops/get_all_locations_drop_down',
        'uses'  => 'ShopController@list_all_locations'
    ]);

    Route::get('admin/shops/cash_terminals', [
        'as'    => 'admin/shops/cash_terminals',
        'uses'  => 'ShopController@cash_terminals'
    ]);

    Route::post('admin/shops/cash_terminals',[
        'as'    => 'admin/shops/new_cash_terminal',
        'uses'  => 'ShopController@add_cash_terminal'
    ]);

    Route::post('admin/shops/location/{id}/opening_hours_update', [
        'as'    => 'admin/shops/location/opening_hours_update',
        'uses'  => 'ShopController@update_opening_hours'
    ]);

    Route::patch('admin/shops/location/{id}/store_details_update', [
        'as'    => 'admin/shops/location/store_details_update',
        'uses'  => 'ShopController@store_details_update'
    ]);

    Route::patch('admin/shops/location/{id}/store_address_update', [
        'as'    => 'admin/shops/location/store_address_update',
        'uses'  => 'ShopController@store_address_update'
    ]);

    Route::post('admin/shops/resources/add', [
        'as'    => 'admin/shops/resources/add',
        'uses'  => 'ShopController@add_new_store_resource'
    ]);
    /** Stop Routes for Shops/Locations */

    /** Start Routes for Products Management */
    Route::get('/admin/shops/products/all',[
        'as'    => 'admin/shops/products/all',
        'uses'  => 'ProductController@list_all'
    ]);

    Route::post('/admin/shops/products/add',[
        'as'    => 'admin/shops/products/add',
        'uses'  => 'ProductController@create'
    ]);

    Route::get('/admin/shops/products/{id}',[
        'as'    => 'admin/shops/products/view',
        'uses'  => 'ProductController@get_product'
    ]);

    Route::put('/admin/shops/products/{id}',[
        'as'    => 'admin/shops/products/update',
        'uses'  => 'ProductController@update_product'
    ]);

    Route::post('/admin/shops/products/get_product_history', [
        'as'    => 'admin/shops/products/get_history',
        'uses'  => 'ProductController@get_product_history'
    ]);

    Route::post('/admin/shops/products/{id}/get_product_inventory', [
        'as'    => 'admin/shops/products/get_inventory',
        'uses'  => 'ProductController@get_product_inventory'
    ]);

    Route::post('/admin/shops/products/{id}/add_document', [
        'as'    => 'admin/shops/products/add_document',
        'uses'  => 'ProductController@add_product_document'
    ]);

    Route::post('/admin/shops/products/{id}/add_image', [
        'as'    => 'admin/shops/products/add_image',
        'uses'  => 'ProductController@add_product_image'
    ]);

    Route::post('/admin/shops/products/get_all_products_inventory', [
        'as'    => 'admin/shops/products/get_all_inventories',
        'uses'  => 'ProductController@get_all_products_inventory'
    ]);

    Route::post('/admin/shops/products/add_to_inventory', [
        'as'    => 'admin/shops/products/add_to_inventory',
        'uses'  => 'ProductController@add_to_inventory'
    ]);

    Route::post('/admin/shops/products/transfer_inventory', [
        'as'    => 'admin/shops/products/transfer_inventory',
        'uses'  => 'ProductController@transfer_from_inventory'
    ]);

    Route::post('/admin/shops/products/ajax_get', [
        'as'    => 'admin/shops/products/ajax_get',
        'uses'  => 'ProductController@ajax_get'
    ]);

    Route::post('/admin/shops/products/{id}/get_from_inventory', [
        'as'    => 'admin/shops/products/get_from_inventory',
        'uses'  => 'ProductController@remove_from_inventory'
    ]);

    Route::put('admin/shops/product/{id}/update_availability', [
        'as'    => 'admin/shops/products/update_availability',
        'uses'  => 'ProductController@update_product_availability'
    ]);

    Route::get('admin/shops/all_inventory', [
        'as'    => 'admin/shops/all_inventory',
        'uses'  => 'ProductController@all_inventory'
    ]);
    /** Stop Routes for Products Management */

    /** Start Routes for new orders */
    Route::get('admin/shops/new_order', [
        'as'    => 'admin/shops/add_new_order',
        'uses'  => 'OrderController@add_order'
    ]);

    Route::post('admin/shops/new_order', [
        'as'    => 'admin/shops/save_new_order',
        'uses'  => 'OrderController@save_order'
    ]);

    Route::get('admin/shop/order/{id}/view', [
        'as'    => 'admin/shops/view_order_details',
        'uses'  => 'OrderController@view_order',
    ]);

    Route::get('admin/shops/list_orders', [
        'as'    =>  'admin/shops/list_orders',
        'uses'  =>  'OrderController@all_orders'
    ]);

    Route::post('admin/shops/orders/ajax_get_details',[
        'as'    => 'admin/shops/orders/ajax_get_details',
        'uses'  => 'OrderController@get_order_details'
    ]);

    Route::post('admin/shops/orders/{id}/ajax_get_details',[
        'as'    => 'admin/shops/orders/ajax_get_details_id',
        'uses'  => 'OrderController@get_order_details'
    ]);

    Route::post('admin/shops/orders/{id}/ajax_get_line_items',[
        'as'    => 'admin/shops/orders/ajax_get_line_items',
        'uses'  => 'OrderController@get_order_lines_items',
    ]);

    Route::post('admin/shops/orders/add_update_line_item', [
        'as'    => 'admin/shops/orders/add_update_line_items',
        'uses'  => 'OrderController@add_update_line_item'
    ]);

    Route::post('admin/shops/orders/get_all_orders', [
        'as'    => 'admin/shops/orders/get_all_ajax_orders',
        'uses'  => 'OrderController@get_all_orders'
    ]);
    /** Stop Routes for new orders */

    /** Start Routes for front users in backend */
    Route::get('admin/front_users/view_all_members', [
        'as'    => 'admin/front_users/view_all_members',
        'uses'  => 'FrontEndUserController@index'
    ]);

    Route::get('admin/front_users/{id}/view_user', [
        'as'    => 'admin/front_users/view_user',
        'uses'  => 'FrontEndUserController@show'
    ]);

    Route::get('admin/front_users/{id}/view_account_settings', [
        'as'    => 'admin/front_users/view_account_settings',
        'uses'  => 'FrontEndUserController@show_account_settings'
    ]);

    Route::get('admin/front_users/{id}/view_bookings', [
        'as'    => 'admin/front_users/view_bookings',
        'uses'  => 'FrontEndUserController@show_bookings'
    ]);

    Route::get('admin/front_users/{id}/view_finance', [
        'as'    => 'admin/front_users/view_finance',
        'uses'  => 'FrontEndUserController@show_finance'
    ]);
    /** Stop Routes for front users in backend */

    /** Start Routes for bookings */
    Route::resource('booking', 'BookingController');

    /** Stop Routes for bookings */
});

Route::group(['prefix'=>'ajax', 'middleware' => 'web'], function(){

    Route::post('get_booking_hours',[
        'as'    => 'ajax/get_booking_hours',
        'uses'  => 'FrontPageController@get_booking_hours'
    ]);

    Route::post('book_resource',[
        'as'    => 'ajax/book_resource',
        'uses'  => 'FrontPageController@book_resource'
    ]);

    Route::post('resources_available_for_date_time',[
        'as'    => 'ajax/get_resource_date_time',
        'uses'  => 'FrontPageController@get_resource_list_for_date_time'
    ]);

    Route::post('booking_confirmed', [
        'as'    => 'ajax/booking-confirm',
        'uses'  => 'BookingController@confirm_booking'
    ]);

    Route::post('booking_canceled', [
        'as'    => 'ajax/booking-canceled',
        'uses'  => 'BookingController@cancel_booking'
    ]);

    Route::post('add_friend_by_phone', [
        'as'    => 'ajax/add_friend_by_phone',
        'uses'  => 'FrontEndUserController@add_friend_by_phone'
    ]);

    Route::post('get_friends_list', [
        'as'    => 'ajax/get_friends_list',
        'uses'  => 'FrontEndUserController@ajax_get_friends_list'
    ]);

    Route::post('get_friends_players_list', [
        'as'    => 'ajax/get_players_list',
        'uses'  => 'FrontEndUserController@ajax_get_available_players_list'
    ]);
});