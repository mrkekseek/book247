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

if (env('FEDERATION',false)){
    include('federation_routes.php');
} else {

    // Federation api requests
    Route::get('api/federation_member_has_valid_license', [
        'as'    => 'api/federation_member_has_valid_license',
        'uses'  => 'Federation\FederationApi@federation_member_has_valid_license'
    ]);

    Route::get('api/federation_list_of_licenses', [
        'as'    => 'api/federation_list_of_licenses',
        'uses'  => 'Federation\FederationApi@federation_list_of_licenses'
    ]);

    Route::get('api/federation_buy_license', [
        'as'    => 'api/federation_buy_license',
        'uses'  => 'Federation\FederationApi@federation_buy_license'
    ]);

    Route::post('api/federation_buy_license', [
        'as'    => 'api/federation_buy_license',
        'uses'  => 'Federation\FederationApi@federation_buy_license'
    ]);


    Route::post('api/register_owner', [
        'as'    => 'api/register_owner',
        'uses'  => 'Federation\FederationApi@register_owner'
    ]);

    Route::post('api/assign_subdomain_settings', [
        'as' => 'api/assign_subdomain_settings',
        'uses' => 'Federation\FederationApi@assign_subdomain_settings'
    ]);

    Route::get('api/status', [
        'as'    => 'api/status',
        'uses'  => 'Federation\FederationApi@status'
    ]);

    // Federation api requests end


    Route::get('buy_license/{token}/{sso_id}/{license_id?}{request_url?}', [
        'as'    => 'buy_license',
        'uses'  => 'MembershipController@iframed'
    ]);

    Route::group(['middleware' => 'apikey', 'prefix' => 'apic'], function () {
        Route::get('status', [
            'as' => 'status',
            'uses' => 'ApicController@status'
        ]);
        Route::post('register_owner', [
            'as' => 'register_owner',
            'uses' => 'ApicController@register_owner'
        ]);
        Route::post('assign_subdomain_settings', [
            'as' => 'assign_subdomain_settings',
            'uses' => 'ApicController@assign_subdomain_settings'
        ]);
        Route::post('get_all_locations_and_resources', [
            'as' => 'get_all_locations_and_resources',
            'uses' => 'ApicController@get_all_locations_and_resources'
        ]);
        Route::post('players_statistics_activity_gender_age', [
            'as' => 'players_statistics_activity_gender_age',
            'uses' => 'ApicController@players_statistics_activity_gender_age'
        ]);
        Route::post('get_latest_registered_players', [
            'as' => 'get_latest_registered_players',
            'uses' => 'ApicController@get_latest_registered_players'
        ]);
        Route::post('get_members_growth', [
            'as' => 'get_members_growth',
            'uses' => 'ApicController@get_members_growth'
        ]);
        Route::post('get_bookings_per_parts_of_day', [
            'as' => 'get_bookings_per_parts_of_day',
            'uses' => 'ApicController@get_bookings_per_parts_of_day'
        ]);
    });

    Route::group(['middleware' => 'web'], function () {
        // default login/register/forgot password routes
        Route::auth();

        Route::get('/', [
            'as' => 'homepage',
            'uses' => 'FrontPageController@index'
        ]);

        Route::get('activate_user/{token}', [
            'as' => 'activate_user',
            'uses' => 'FrontEndUserController@activate_user_by_token'
        ]);

        Route::get('resend_activation_email/{id}', [
            'as' => 'resend_activation_email',
            'uses' => 'FrontEndUserController@resend_activation_email'
        ]);

        Route::post('login', [
            'as' => 'front_login',
            'uses' => 'FrontPageController@authenticate'
        ]);

        Route::get('logout', 'FrontEndUserController@logout');

        //Route::get('login-new', function () {
        //    return view('login');
        //});

        Route::get('admin/registration', [
            'as' => 'admin/registration',
            'uses' => 'BackEndUserController@registrationStepsIndex']
        );

        Route::post('admin/registration', [
            'as' => 'admin/registration',
            'uses' => 'BackEndUserController@registrationStepsSave']
        );

        Route::get('admin/login', [
            'as' => 'admin/login',
            function () {
                return view('admin/auth/login');
            }]);

        Route::post('admin/login', [
                'as' => 'admin/login',
                'uses' => 'AdminController@authenticate']
        );

        Route::post('admin/ajax_login', [
                'as' => 'admin/ajax_login',
                'uses' => 'AdminController@ajax_authenticate']
        );

        Route::get('admin/logout', [
            'as' => 'admin/logout',
            'uses' => 'AdminController@logout'
        ]);

        //Route::get('/admin/text-auth', 'HomeController@index');

        Route::get('/admin', [
            'as' => 'admin',
            'uses' => 'AdminController@index'
        ]);


        Route::get('/admin-home-public', [
            'as' => 'admin-home-public',
            'uses' => 'AdminController@public_index'
        ]);

        Route::get('/admin/error/permission_denied', [
            'as' => 'admin/error/permission_denied',
            'uses' => 'AdminController@permission_denied'
        ]);

        Route::get('/admin/error/not_found', [
            'as' => 'admin/error/not_found',
            'uses' => 'AdminController@not_found'
        ]);

        /** Start - Back end users route */
        Route::get('/admin/back_users/', [
            'as' => 'admin/back_users',
            'uses' => 'BackEndUserController@index'
        ]);

        Route::put('/admin/back_users/add_user', [
            'as' => 'admin/back_users/add_user',
            'uses' => 'BackEndUserController@create'
        ]);

        Route::put('/admin/front_users/add_user', [
            'as' => 'admin/front_users/add_user',
            'uses' => 'FrontEndUserController@store'
        ]);

        Route::get('/admin/back_users/view_user/{id}', [
            'as' => 'admin/back_users/view_user/',
            'uses' => 'BackEndUserController@show',
        ]);

        Route::post('admin/back_users/view_user/{id}/acc_info', [
            'as' => 'admin/back_users/view_user/acc_info',
            'uses' => 'BackEndUserController@update_account_info',
        ]);

        Route::post('admin/back_users/remove_avatar', [
            'as' => 'admin/back_users/remove_avatar',
            'uses' => 'BackEndUserController@remove_avatar',
        ]);

        Route::post('admin/front_users/view_user/{id}/personal_info', [
            'as' => 'admin/front_users/view_user/personal_info',
            'uses' => 'FrontEndUserController@update_personal_info',
        ]);
        Route::post('admin/front_users/view_user/{id}/allowed_personal_info', [
            'as' => 'admin/front_users/view_user/allowed_personal_info',
            'uses' => 'FrontEndUserController@update_allowed_personal_info',
        ]);

        Route::post('admin/back_users/view_user/{id}/personal_info', [
            'as' => 'admin/back_users/view_user/personal_info',
            'uses' => 'BackEndUserController@update_personal_info',
        ]);

        Route::post('admin/back_users/view_user/{id}/password_update', [
            'as' => 'admin/back_users/view_user/password_update',
            'uses' => 'BackEndUserController@updatePassword',
        ]);

        Route::post('admin/front_users/view_user/{id}/password_update', [
            'as' => 'admin/front_users/view_user/password_update',
            'uses' => 'FrontEndUserController@updatePassword',
        ]);

        Route::post('admin/back_users/view_user/{id}/personal_address', [
            'as' => 'admin/back_users/view_user/personal_address',
            'uses' => 'BackEndUserController@update_personal_address',
        ]);

        Route::post('admin/front_users/view_user/{id}/personal_address', [
            'as' => 'admin/front_users/view_user/personal_address',
            'uses' => 'FrontEndUserController@update_personal_address',
        ]);

        Route::post('admin/back_users/view_user/{id}/avatar_image', [
            'as' => 'admin/back_users/view_user/avatar_image',
            'uses' => 'BackEndUserController@update_personal_avatar',
        ]);

        Route::post('admin/front_users/view_user/{id}/avatar_image', [
            'as' => 'admin/front_users/view_user/avatar_image',
            'uses' => 'FrontEndUserController@update_account_avatar',
        ]);

        Route::post('admin/back_users/view_user/{id}/add_document', [
            'as' => 'admin/back_users/view_user/add_document',
            'uses' => 'BackEndUserController@add_account_document',
        ]);

        Route::post('admin/front_users/view_user/{id}/add_document', [
            'as' => 'admin/front_users/view_user/add_document',
            'uses' => 'FrontEndUserController@add_account_document',
        ]);

        Route::get('admin/back_users/{id}/get_document/{document_name}', [
            'as' => 'admin/back_user/get_document',
            'uses' => 'BackEndUserController@get_user_account_document'
        ]);

        Route::get('admin/front_users/{id}/get_document/{document_name}', [
            'as' => 'admin/front_user/get_document',
            'uses' => 'FrontEndUserController@get_user_account_document'
        ]);

        /* Start - Stripe routes */
        Route::post('charge_customer', [
            'as' => 'charge_customer',
            'uses' => 'StripeController@chargeCustomer']
        );

        Route::post('pay_with_stripe', [
            'as' => 'pay_with_stripe',
            'uses' => 'FrontEndUserController@pay_with_stripe']
        );
        /* End - Stripe routes */

        //** Start - Back end users route */

        Route::post('admin/users/ajax_get_info', [
            'as' => 'admin/users/ajax_get_info',
            'uses' => 'BackEndUserController@ajax_get_user_info'
        ]);

        Route::post('admin/users/ajax_get_users', [
            'as' => 'admin/users/ajax_get_users',
            //'uses'  => 'BackEndUserController@ajax_get_users'
            'uses' => 'BackEndUserController@ajax_get_users_optimized'
        ]);

        Route::post('admin/users/ajax_get_bill_address', [
            'as' => 'admin/users/ajax_get_bill_address',
            'uses' => 'BackEndUserController@ajax_get_bill_address'
        ]);

        Route::post('admin/users/ajax_get_ship_address', [
            'as' => 'admin/users/ajax_get_ship_address',
            'uses' => 'BackEndUserController@ajax_get_ship_address'
        ]);

        /** Routes for employees and backend users roles */
        Route::get('/admin/back_users/user_roles', [
            'as' => 'admin/back_users/user_roles',
            'uses' => 'RolesController@all_users_roles'
        ]);

        Route::get('/admin/back_users/roles_permissions', [
            'as' => 'admin/back_users/roles_permissions',
            'uses' => 'RolesController@list_permissions'
        ]);

        Route::post('/admin/back_users/roles_permissions', [
            'as' => 'admin/back_users/roles_permissions',
            'uses' => 'RolesController@add_permission'
        ]);

        Route::get('/admin/back_users/roles_permissions/{id}', [
            'as' => 'admin/back_users/roles_permissions/view',
            'uses' => 'RolesController@view_permission'
        ]);

        Route::put('/admin/back_users/roles_permissions/{id}', [
            'as' => 'admin/back_users/roles_permissions/view',
            'uses' => 'RolesController@update_permission'
        ]);

        Route::post('/admin/back_users/user_roles', [
            'as' => 'admin/back_users/user_roles',
            'uses' => 'RolesController@add_user_role'
        ]);

        Route::put('/admin/back_users/user_roles', [
            'as' => 'admin/back_users/user_roles',
            'uses' => 'RolesController@update_user_role'
        ]);

        Route::delete('/admin/back_users/user_roles', [
            'as' => 'admin/back_users/user_roles',
            'uses' => 'RolesController@delete_user_role'
        ]);
        /** End Routes for employees roles */

        /** Start Routes for Shops/Locations */
        Route::get('/admin/shops/locations', [
            'as' => 'admin/shops/locations/all',
            'uses' => 'ShopController@list_all'
        ]);

        Route::get('/admin/shops/locations/{id}', [
            'as' => 'admin/shops/locations/view',
            'uses' => 'ShopController@get_shop_location'
        ]);

        Route::get('/admin/shops/resources/{id}', [
            'as' => 'admin/shops/resources/view',
            'uses' => 'ShopController@get_shop_resource'
        ]);

        Route::post('/admin/shops/locations/add', [
            'as' => 'admin/shops/locations/add',
            'uses' => 'ShopController@add_shop_location'
        ]);

        Route::get('admin/shops/employee_work_plan', [
            'as' => 'admin/shops/employee_work_plan',
            'uses' => 'ShopController@shops_employee_working_plan'
        ]);

        Route::post('admin/shops/get_all_locations', [
            'as' => 'admin/shops/get_all_locations_drop_down',
            'uses' => 'ShopController@list_all_locations'
        ]);

        Route::get('admin/shops/cash_terminals', [
            'as' => 'admin/shops/cash_terminals',
            'uses' => 'ShopController@cash_terminals'
        ]);

        Route::post('admin/shops/cash_terminals', [
            'as' => 'admin/shops/new_cash_terminal',
            'uses' => 'ShopController@add_cash_terminal'
        ]);

        Route::post('admin/shops/add_opening_hours', [
            'as' => 'admin/shops/add_opening_hours',
            'uses' => 'ShopController@add_opening_hours'
        ]);

        Route::post('admin/shops/get_opening_hours_details', [
            'as' => 'admin/shops/get_opening_hours_details',
            'uses' => 'ShopController@get_opening_hours_details'
        ]);

        Route::post('admin/shops/update_opening_hours', [
            'as' => 'admin/shops/update_opening_hours',
            'uses' => 'ShopController@update_opening_hours'
        ]);

        Route::post('admin/shops/delete_opening_hours', [
            'as' => 'admin/shops/delete_opening_hours',
            'uses' => 'ShopController@delete_opening_hours'
        ]);

        Route::patch('admin/shops/location/{id}/store_details_update', [
            'as' => 'admin/shops/location/store_details_update',
            'uses' => 'ShopController@store_details_update'
        ]);

        Route::patch('admin/shops/location/{id}/store_address_update', [
            'as' => 'admin/shops/location/store_address_update',
            'uses' => 'ShopController@store_address_update'
        ]);

        Route::post('admin/shops/resources/add', [
            'as' => 'admin/shops/resources/add',
            'uses' => 'ShopController@add_new_store_resource'
        ]);

        Route::post('admin/shops/resource/update', [
            'as' => 'admin/shops/resource/update',
            'uses' => 'ShopController@update_store_resource'
        ]);

        Route::post('admin/shops/resources/delete', [
            'as' => 'admin/shops/resources/delete',
            'uses' => 'ShopController@delete_store_resource'
        ]);

        Route::post('admin/shops/resource/add_price', [
            'as' => 'admin/shops/resource/add_price',
            'uses' => 'ShopController@add_resource_price'
        ]);

        Route::post('admin/shops/resource/update_resource_price', [
            'as' => 'admin/shops/resource/update_resource_price',
            'uses' => 'ShopController@update_resource_price'
        ]);

        Route::post('admin/shops/resource/delete_resource_price', [
            'as' => 'admin/shops/resource/delete_resource_price',
            'uses' => 'ShopController@delete_resource_price'
        ]);

        Route::post('admin/shops/resource/get_resource_price_details', [
            'as' => 'admin/shops/resource/get_resource_price_details',
            'uses' => 'ShopController@get_resource_price_details'
        ]);

        Route::post('admin/shops/resource/copy_resource_prices', [
            'as' => 'admin/shops/resource/copy_resource_prices',
            'uses' => 'ShopController@copy_resource_prices'
        ]);

        Route::get('admin/shops/inventory_and_transfer', [
            'as' => 'admin/shops/inventory_and_transfer',
            'uses' => 'ShopController@all_inventory_make_transfer'
        ]);

        Route::post('admin/shops/shop_system_option_update', [
            'as' => 'admin/shops/shop_system_option_update',
            'uses' => 'ShopController@system_option_update'
        ]);

        /** Stop Routes for Shops/Locations */

        /** Start Routes for Products Management */
        Route::get('/admin/shops/products/all', [
            'as' => 'admin/shops/products/all',
            'uses' => 'ProductController@list_all'
        ]);

        Route::post('/admin/shops/products/add', [
            'as' => 'admin/shops/products/add',
            'uses' => 'ProductController@create'
        ]);

        Route::get('/admin/shops/products/{id}', [
            'as' => 'admin/shops/products/view',
            'uses' => 'ProductController@get_product'
        ]);

        Route::put('/admin/shops/products/{id}', [
            'as' => 'admin/shops/products/update',
            'uses' => 'ProductController@update_product'
        ]);

        Route::post('/admin/shops/products/get_product_history', [
            'as' => 'admin/shops/products/get_history',
            'uses' => 'ProductController@get_product_history'
        ]);

        Route::post('/admin/shops/products/{id}/get_product_inventory', [
            'as' => 'admin/shops/products/get_inventory',
            'uses' => 'ProductController@get_product_inventory'
        ]);

        Route::post('/admin/shops/products/{id}/add_document', [
            'as' => 'admin/shops/products/add_document',
            'uses' => 'ProductController@add_product_document'
        ]);

        Route::post('/admin/shops/products/{id}/add_image', [
            'as' => 'admin/shops/products/add_image',
            'uses' => 'ProductController@add_product_image'
        ]);

        Route::post('/admin/shops/products/get_all_products_inventory', [
            'as' => 'admin/shops/products/get_all_inventories',
            'uses' => 'ProductController@get_all_products_inventory'
        ]);

        Route::post('/admin/shops/products/add_to_inventory', [
            'as' => 'admin/shops/products/add_to_inventory',
            'uses' => 'ProductController@add_to_inventory'
        ]);

        Route::post('/admin/shops/products/transfer_inventory', [
            'as' => 'admin/shops/products/transfer_inventory',
            'uses' => 'ProductController@transfer_from_inventory'
        ]);

        Route::post('/admin/shops/products/ajax_get', [
            'as' => 'admin/shops/products/ajax_get',
            'uses' => 'ProductController@ajax_get'
        ]);

        Route::post('/admin/shops/products/{id}/get_from_inventory', [
            'as' => 'admin/shops/products/get_from_inventory',
            'uses' => 'ProductController@remove_from_inventory'
        ]);

        Route::put('admin/shops/product/{id}/update_availability', [
            'as' => 'admin/shops/products/update_availability',
            'uses' => 'ProductController@update_product_availability'
        ]);

        Route::get('admin/shops/all_inventory', [
            'as' => 'admin/shops/all_inventory',
            'uses' => 'ProductController@all_inventory'
        ]);
        /** Stop Routes for Products Management */

        /** Start Routes for new orders */
        Route::get('admin/shops/new_order', [
            'as' => 'admin/shops/add_new_order',
            'uses' => 'OrderController@add_order'
        ]);

        Route::post('admin/shops/new_order', [
            'as' => 'admin/shops/save_new_order',
            'uses' => 'OrderController@save_order'
        ]);

        Route::get('admin/shop/order/{id}/view', [
            'as' => 'admin/shops/view_order_details',
            'uses' => 'OrderController@view_order',
        ]);

        Route::get('admin/shops/list_orders', [
            'as' => 'admin/shops/list_orders',
            'uses' => 'OrderController@all_orders'
        ]);

        Route::post('admin/shops/orders/ajax_get_details', [
            'as' => 'admin/shops/orders/ajax_get_details',
            'uses' => 'OrderController@get_order_details'
        ]);

        Route::post('admin/shops/orders/{id}/ajax_get_details', [
            'as' => 'admin/shops/orders/ajax_get_details_id',
            'uses' => 'OrderController@get_order_details'
        ]);

        Route::post('admin/shops/orders/{id}/ajax_get_line_items', [
            'as' => 'admin/shops/orders/ajax_get_line_items',
            'uses' => 'OrderController@get_order_lines_items',
        ]);

        Route::post('admin/shops/orders/add_update_line_item', [
            'as' => 'admin/shops/orders/add_update_line_items',
            'uses' => 'OrderController@add_update_line_item'
        ]);

        Route::post('admin/shops/orders/get_all_orders', [
            'as' => 'admin/shops/orders/get_all_ajax_orders',
            'uses' => 'OrderController@get_all_orders'
        ]);
        /** Stop Routes for new orders */

        /** Start Routes for front users in backend */
        Route::get('admin/front_users/view_all_members', [
            'as' => 'admin/front_users/view_all_members',
            'uses' => 'FrontEndUserController@all_front_members_list'
        ]);

        Route::get('admin/bookings/dropins-rules', [
            'as' => 'admin/bookings/dropins-rules',
            'uses' => 'MembershipPlansController@dropins_rules'
        ]);

        Route::get('admin/front_users/view_all_members_new', [
            'as' => 'admin/front_users/view_all_members_new',
            'uses' => 'FrontEndUserController@all_front_members_list'
        ]);

        Route::get('admin/front_users/register_new', [
            'as' => 'admin/front_users/register_new',
            'uses' => 'FrontEndUserController@create'
        ]);

        Route::get('admin/front_users/test_new', [
            'as' => 'admin/front_users/test_new',
            'uses' => 'FrontEndUserController@test_code_for_invoices'
        ]);

        Route::get('admin/front_users/import_members', [
            'as' => 'admin/front_users/import_members',
            'uses' => 'FrontEndUserController@import_from_file'
        ]);

        Route::post('admin/front_users/import_members', [
            'as' => 'admin/front_users/import_members',
            'uses' => 'FrontEndUserController@import_from_file'
        ]);

        Route::put('admin/front_users/import_members', [
            'as' => 'admin/front_users/import_members',
            'uses' => 'FrontEndUserController@import_from_file'
        ]);

        Route::get('admin/front_users/{id}/view_user', [
            'as' => 'admin/front_users/view_user',
            'uses' => 'FrontEndUserController@show'
        ]);

        Route::get('admin/front_users/{id}/view_account_settings', [
            'as' => 'admin/front_users/view_account_settings',
            'uses' => 'FrontEndUserController@show_account_settings'
        ]);

        Route::post('admin/front_users/unlink_sso_account', [
            'as' => 'unlink_sso_account',
            'uses' => 'FrontEndUserController@unlink_sso_account'
        ]);


        Route::get('admin/front_users/{id}/view_personal_settings', [
            'as' => 'admin/front_users/view_personal_settings',
            'uses' => 'FrontEndUserController@show_personal_settings'
        ]);

        Route::get('admin/front_users/{id}/view_bookings', [
            'as' => 'admin/front_users/view_bookings',
            'uses' => 'FrontEndUserController@show_bookings'
        ]);



        Route::get('admin/front_users/{id}/view_finance', [
            'as' => 'admin/front_users/view_finance',
            'uses' => 'FrontEndUserController@show_finance'
        ]);

        Route::get('admin/front_users/{id}/bookings_statistic', [
            'as' => 'ajax/player_bookings_statistic',
            'uses' => 'BookingController@get_player_statistics'
        ]);
        /** Stop Routes for front users in backend */

        /** Start Routes for bookings */
        Route::resource('booking', 'BookingController');

        Route::get('admin/bookings/location_calendar_day_view/{day}/', [
            'as' => 'bookings/location_calendar_day_view',
            'uses' => 'BookingController@location_calendar_day_view'
        ]);

        Route::get('admin/bookings/location_calendar_day_view/{day}/{location}/{activity}/', [
            'as' => 'bookings/location_calendar_day_view_all',
            'uses' => 'BookingController@location_calendar_day_view'
        ]);
        /** Stop Routes for bookings */

        /** Start Finance Part */
        Route::get('admin/invoices', [
            'as' => 'admin/invoices',
            'uses' => 'InvoiceController@list_all_invoices'
        ]);

        Route::get('admin/invoices/{id}/view', [
            'as' => 'admin/invoices/view',
            'uses' => 'InvoiceController@view_invoice'
        ]);
        /** Stop Finance Part */

        Route::get('admin/activity_log', [
            'as' => 'admin/activity_log',
            'uses' => 'ActivityLog@show_activity_log'
        ]);

        Route::post('back_end_user/get_activity_log', [
            'as' => 'ajax/get_activity_log',
            'uses' => 'ActivityLog@get_activity_log'
        ]);
        
        Route::get('admin/invoices_log', [
            'as' => 'admin/invoices_log',
            'uses' => 'InvoiceController@show_invoices_log'
        ]);

        Route::post('admin/invoices_log', [
            'as' => 'ajax/get_invoices_log',
            'uses' => 'InvoiceController@get_invoices_log'
        ]);

        /* Start General Settings Part */
        Route::get('admin/settings/list_all', [
            'as' => 'admin/settings/list_all',
            'uses' => 'AppSettings@index'
        ]);

        Route::get('admin/settings/manage_settings', [
            'as' => 'admin/settings/manage_settings',
            'uses' => 'AppSettings@manage_settings'
        ]);

        Route::post('admin/settings/manage_settings/clear_cache', [
            'as' => 'admin/settings/manage_settings/clear_cache',
            'uses' => 'AppSettings@clear_cache'
        ]);

        Route::get('admin/settings/account_key', [
            'as'    => 'admin/settings/account_key',
            'uses'  => 'AppSettings@rankedin_app_key_integration'
        ]);

        Route::post('admin/settings/update_point', [
            'as'    => 'admin/settings/update_point',
            'uses'  => 'AppSettings@update_point'
        ]);

        Route::post('admin/settings/get_point', [
            'as'    => 'admin/settings/get_point',
            'uses'  => 'AppSettings@get_point'
        ]);

        Route::post('admin/settings/remove_point', [
            'as'    => 'admin/settings/remove_point',
            'uses'  => 'AppSettings@remove_point'
        ]);
        /* Stop General Settings Part */

        /* Start Finance Profiles Part */
        Route::get('admin/settings_financial_profiles/list_all', [
            'as' => 'admin/settings_financial_profiles/list_all',
            'uses' => 'FinancialProfiles@list_all'
        ]);

        Route::get('admin/settings_financial_profiles/add', [
            'as' => 'admin/settings_financial_profiles/add',
            'uses' => 'FinancialProfiles@add_shop_financial_profile'
        ]);

        Route::post('admin/settings_financial_profiles/create', [
            'as' => 'admin/settings_financial_profiles/create',
            'uses' => 'FinancialProfiles@store_shop_financial_profile'
        ]);

        Route::get('admin/settings_financial_profiles/{id}/show', [
            'as' => 'admin/settings_financial_profiles/show',
            'uses' => 'FinancialProfiles@show_shop_financial_profile'
        ]);

        Route::get('admin/settings_financial_profiles/{id}/edit', [
            'as' => 'admin/settings_financial_profiles/edit',
            'uses' => 'FinancialProfiles@edit_shop_financial_profile'
        ]);

        Route::post('admin/settings_financial_profiles/{id}/update', [
            'as' => 'admin/settings_financial_profiles/update',
            'uses' => 'FinancialProfiles@update_shop_financial_profile'
        ]);
        /* Stop Finance Profiles Part */

        /* Start Email Templates Part */
        Route::get('admin/templates_email/list_all', [
            'as' => 'admin/templates_email/list_all',
            'uses' => 'EmailsController@list_all'
        ]);

        Route::get('/admin/templates_email/add', [
            'as' => 'admin/templates_email/add',
            'uses' => 'EmailsController@add'
        ]);

        Route::get('admin/templates_email/edit/{id}', [
            'as' => 'admin/templates_email/edit/{id}',
            'uses' => 'EmailsController@edit'
        ]);

        Route::post('admin/templates_email/create', [
            'as' => 'admin/templates_email/create',
            'uses' => 'EmailsController@store_email_template'
        ]);

        Route::post('admin/templates_email/update/{id}', [
            'as' => 'admin/templates_email/update/{id}',
            'uses' => 'EmailsController@update_email_template'
        ]);

        Route::get('admin/templates_email/reset_default/{id}', [
            'as' => 'admin/templates_email/reset_default/{id}',
            'uses' => 'EmailsController@reset_default'
        ]);

        Route::get('admin/templates_email/make_default/{id}', [
            'as' => 'admin/templates_email/make_default/{id}',
            'uses' => 'EmailsController@make_default'
        ]);

        Route::post('admin/templates_email/delete', [
            'as' => 'admin/templates_email/delete',
            'uses' => 'EmailsController@delete_email_template'
        ]);
        /* Stop Email Templates Part */
    });

    Route::group(['middleware' => 'web', 'prefix' => 'admin'], function () {
        /** Start - Membership Management */

        Route::resource('membership_plan', 'MembershipPlansController');
        //GET 	        /membership_plan 	        index 	    admin.membership_plan.index
        //GET 	        /membership_plan/create 	create 	    admin.membership_plan.create
        //POST 	        /membership_plan 	        store 	    admin.membership_plan.store
        //GET 	        /membership_plan/{id} 	    show 	    admin.membership_plan.show
        //GET 	        /membership_plan/{id}/edit 	edit 	    admin.membership_plan.edit
        //PUT/PATCH 	/membership_plan/{id} 	    update 	    admin.membership_plan.update
        //DELETE 	    /membership_plan/{id} 	    destroy 	admin.membership_plan.destroy

        Route::resource('store_credit_products', 'StoreCreditProductsController');
         //GET          /store_credit_products            index       admin.store_credit_products.index
        //GET           /store_credit_products/create     create      admin.store_credit_products.create
        //POST          /store_credit_products            store       admin.store_credit_products.store
        //GET           /store_credit_products/{id}       show        admin.store_credit_products.show
        //GET           /store_credit_products/{id}/edit  edit        admin.store_credit_products.edit
        //PUT/PATCH     /store_credit_products/{id}       update      admin.store_credit_products.update
        //DELETE        /store_credit_products/{id}       destroy     admin.membership_plan.destroy

        Route::post('store_credit_products/change_status', [
            'as' => 'store_credit_products-change_status',
            'uses' => 'StoreCreditProductsController@store_credit_change_status'
        ]);


        Route::post('membership_plan/add_restriction', [
            'as' => 'membership_plan-add_restriction',
            'uses' => 'MembershipPlansController@add_plan_restriction'
        ]);

        Route::post('membership_plan/remove_restriction', [
            'as' => 'membership_plan-remove_restriction',
            'uses' => 'MembershipPlansController@remove_plan_restriction'
        ]);

        Route::post('membership_plan/resync_restriction', [
            'as' => 'membership_plan-resync_restriction',
            'uses' => 'MembershipPlansController@resync_restriction'
        ]);

        Route::post('membership_plan/resync_member_restriction', [
            'as' => 'membership_plan-resync_member_restriction',
            'uses' => 'MembershipPlansController@resync_member_restriction'
        ]);

        Route::post('membership_plan/ajax_get_details', [
            'as' => 'admin/membership_plans/ajax_get_details',
            'uses' => 'MembershipPlansController@ajax_get_plan_details'
        ]);

        Route::post('membership_plans/assign_to_member', [
            'as' => 'admin/membership_plans/assign_to_member',
            'uses' => 'MembershipController@assign_membership_to_member'
        ]);

        Route::post('membership_plans/changed_active_plan', [
            'as' => 'admin/membership_plans/changed_active_plan',
            'uses' => 'MembershipController@change_active_membership_for_member'
        ]);

        Route::post('membership_plans/freeze_member_plan', [
            'as' => 'admin/membership_plans/freeze_member_plan',
            'uses' => 'MembershipController@freeze_membership_for_member'
        ]);

        Route::post('membership_plans/unfreeze_member_plan', [
            'as' => 'admin/membership_plans/unfreeze_member_plan',
            'uses' => 'MembershipController@unfreeze_membership_for_member'
        ]);

        Route::post('membership_plans/cancel_member_plan', [
            'as' => 'admin/membership_plans/cancel_member_plan',
            'uses' => 'MembershipController@cancel_membership_for_member'
        ]);

        Route::post('membership_plans/delete_pending_action', [
            'as' => 'admin/membership_plans/delete_pending_action',
            'uses' => 'MembershipController@cancel_membership_planned_action'
        ]);

        Route::get('membership_products/list_all', [
            'as' => 'admin/membership_products/list_all',
            'uses' => 'MembershipProductsController@index'
        ]);

        Route::get('membership_products/add_new', [
            'as' => 'admin/membership_products/add_new',
            'uses' => 'MembershipProductsController@create'
        ]);

        Route::post('membership_products/add_new', [
            'as' => 'admin/membership_products/add_new',
            'uses' => 'MembershipProductsController@store'
        ]);

        Route::get('membership_products/{id}', [
            'as' => 'admin/membership_products/view',
            'uses' => 'MembershipProductsController@show'
        ]);

        Route::get('membership_products/{id}/edit', [
            'as' => 'admin/membership_products/edit',
            'uses' => 'MembershipProductsController@edit'
        ]);

        Route::post('membership_products/{id}/update', [
            'as' => 'admin/membership_products/update',
            'uses' => 'MembershipProductsController@update'
        ]);

        Route::get('invoices/payment/{id}', [
            'as' => 'admin/invoices/payment/{id}',
            'uses' => 'BackEndUserController@invoice_payment'
        ]);
        Route::get('backend_reset_password/{token}', [
            'as' => 'backend_reset_password',
            'uses' => 'BackEndUserController@password_reset_form'
        ]);

        /** Stop  - Membership Management */
    });

    Route::post('payment/paypal-ipn', [
        'as'    => 'payment/paypal-ipn',
        'uses'  => 'IPN@membership_ipn'
    ]);

    Route::get('payment/paypal_success', [
        'as'    => 'payment/paypal_success',
        'uses'  => 'IPN@membership_paypal_success'
    ]);

    Route::post('payment/paypal_success', [
        'as'    => 'payment/paypal_success',
        'uses'  => 'IPN@membership_paypal_success'
    ]);

    Route::get('payment/paypal_cancel', [
        'as'    => 'payment/paypal_cancel',
        'uses'  => 'IPN@membership_paypal_cancel'
    ]);

    /** Start Routes for front end */
    Route::group(['middleware' => 'web', 'prefix' => 'front'], function () {
        Route::get('my_bookings', [
            'as' => 'front/my_bookings',
            'uses' => 'BookingController@front_my_bookings'
        ]);

        Route::get('finance/invoice/{id}', [
            'as' => 'front/finance/invoice/id',
            'uses' => 'FrontEndUserController@invoice_payment'
        ]);


        Route::post('finance/invoice', [
            'as' => 'front/finance/invoice',
            'uses' => 'FrontEndUserController@post_invoice_payment'
        ]);

        Route::get('bookings_archive', [
            'as' => 'front/bookings_archive',
            'uses' => 'BookingController@front_bookings_archive'
        ]);

        Route::post('bookings_archive', [
            'as' => 'front/bookings_archive',
            'uses' => 'BookingController@get_user_booking_archive'
        ]);

        Route::get('friends_list', [
            'as' => 'front/member_friend_list',
            'uses' => 'FrontEndUserController@member_friends_list'
        ]);

        Route::get('invoice_list', [
            'as' => 'front/member_invoice_list',
            'uses' => 'FrontEndUserController@front_invoice_list'
        ]);

        Route::post('invoice_list', [
            'as' => 'front/member_invoice_list',
            'uses' => 'FrontEndUserController@get_user_invoice_list'
        ]);

        Route::post('transaction_list', [
            'as' => 'front/member_transaction_list',
            'uses' => 'FrontEndUserController@get_user_transaction_list'
        ]);

        Route::get('view_invoice/{id}', [
            'as' => 'front/view_invoice/',
            'uses' => 'FrontEndUserController@front_show_invoice'
        ]);

        Route::get('list_of_memberships', [
            'as' => 'front/membership_types',
            'uses' => 'FrontEndUserController@type_of_memberships'
        ]);

        Route::get('list_of_store_credit', [
            'as' => 'front/store_credit',
            'uses' => 'FrontEndUserController@type_of_store_credit'
        ]);

        Route::get('credit_status', [
            'as' => 'front/credit_status',
            'uses' => 'FrontEndUserController@current_credit_status'
        ]);


        Route::get('contact', [
            'as' => 'front/contact_locations',
            'uses' => 'FrontEndUserController@contact_locations'
        ]);

        Route::post('contact', [
            'as' => 'front/post_contact_locations',
            'uses' => 'FrontEndUserController@post_contact_locations'
        ]);

        Route::get('active_membership', [
            'as' => 'front/active_membership',
            'uses' => 'FrontEndUserController@member_active_membership'
        ]);

        Route::post('buy_store_credit', [
            'as' => 'front/buy_store_credit',
            'uses' => 'FrontEndUserController@buy_store_credit_ajax_call'
        ]);

        Route::post('singup_membership_plan', [
            'as' => 'front/singup_membership_plan',
            'uses' => 'FrontEndUserController@singup_membership_plan_ajax_call'
        ]);

        Route::post('membership_plan/cancel_member_plan', [
            'as' => 'front/membership_plan/cancel_member_plan',
            'uses' => 'MembershipController@cancel_membership_for_member'
        ]);

        Route::get('calendar_booking/{day}/', [
            'as' => 'front_calendar_booking',
            'uses' => 'BookingController@front_bookings_calendar_view'
        ]);

        Route::get('calendar_booking/{day}/{location}/{activity}/', [
            'as' => 'front_calendar_booking_all',
            'uses' => 'BookingController@front_bookings_calendar_view'
        ]);

        Route::get('settings/account', [
            'as' => 'settings/account',
            'uses' => 'FrontEndUserController@settings_account'
        ]);

        Route::get('settings/personal', [
            'as' => 'settings/personal',
            'uses' => 'FrontEndUserController@settings_personal'
        ]);

        Route::post('settings/personal/info', [
            'as' => 'settings/personal/info',
            'uses' => 'FrontEndUserController@settings_personal_info'
        ]);

        Route::post('settings/personal/change_email', [
            'as' => 'settings/personal/change_email',
            'uses' => 'FrontEndUserController@change_email'
        ]);

        Route::post('settings/personal/avatar', [
            'as' => 'settings/personal/avatar',
            'uses' => 'FrontEndUserController@settings_personal_avatar'
        ]);

        //settings/personal/remove_avatar

         Route::post('settings/personal/remove_avatar', [
            'as' => 'settings/personal/remove_avatar',
            'uses' => 'FrontEndUserController@settings_personal_remove_avatar'
        ]);

        Route::post('settings/personal/update_password', [
            'as' => 'settings/personal/update_password',
            'uses' => 'FrontEndUserController@settings_personal_update_password'
        ]);

        Route::get('reset_password/{token}', [
            'as' => 'reset_password',
            'uses' => 'FrontEndUserController@password_reset_form'
        ]);


        Route::get('my_messages', [
            'as' => 'my_messages',
            'uses' => 'FrontEndUserController@front_view_all_messages'
        ]);

        Route::post('reset_password/{token}', [
            'as' => 'reset_password',
            'uses' => 'FrontEndUserController@password_reset_action'
        ]);

        Route::get('error_404', [
            'as' => 'error_404',
            'uses' => 'FrontPageController@error_404'
        ]);

        Route::get('back_error_404', [
            'as' => 'back_error_404',
            'uses' => 'AdminController@error_404'
        ]);

        Route::get('terms_of_service', [
            'as' => 'terms_of_service',
            'uses' => 'FrontPageController@terms_of_service'
        ]);

        Route::get('privacy_policy', [
            'as' => 'privacy_policy',
            'uses' => 'FrontPageController@privacy_policy'
        ]);
        
        Route::get('unsubscribe/{token}', [
            'as' => 'unsubscribe_form',
            'uses' => 'FrontEndUserController@unsubscribe_form'
        ]);
        
        Route::post('unsubscribe/{token}', [
            'as' => 'unsubscribe_action',
            'uses' => 'FrontEndUserController@unsubscribe_action'
        ]);

        Route::get('get_points', [
            'as' => 'get_points',
            'uses' => 'FrontEndUserController@get_points'
        ]);

    });
    /** Stop Routes for front end */

    Route::group(['middleware' => 'web', 'prefix' => 'ajax'], function () {

        Route::post('get_booking_hours', [
            'as' => 'ajax/get_booking_hours',
            'uses' => 'FrontPageController@get_booking_hours'
        ]);

        Route::post('get_rooms_for_activity', [
            'as' => 'ajax/get_rooms_for_activity',
            'uses' => 'ShopResourceController@get_rooms_for_activity'
        ]);

        Route::post('book_resource', [
            'as' => 'ajax/book_resource',
            'uses' => 'FrontPageController@book_resource'
        ]);

        Route::post('resources_available_for_date_time', [
            'as' => 'ajax/get_resource_date_time',
            'uses' => 'FrontPageController@get_resource_list_for_date_time'
        ]);

        Route::post('pay_invoice_with_credit', [
            'as' => 'pay_invoice_with_credit',
            'uses' => 'FrontEndUserController@pay_invoice_with_credit'
        ]);

        Route::post('booking_confirmed', [
            'as' => 'ajax/booking-confirm',
            'uses' => 'BookingController@confirm_booking'
        ]);

        Route::post('booking_canceled', [
            'as' => 'ajax/booking-canceled',
            'uses' => 'BookingController@cancel_booking'
        ]);

        Route::post('confirm_many', [
            'as' => 'ajax/confirm_bookings',
            'uses' => 'BookingController@confirm_bookings'
        ]);

        Route::post('cancel_many_bookings', [
            'as' => 'ajax/cancel_many_bookings',
            'uses' => 'BookingController@cancel_many_bookings'
        ]);

        Route::post('cancel_many', [
            'as' => 'ajax/cancel_bookings',
            'uses' => 'BookingController@cancel_pending_bookings'
        ]);

        Route::post('cancel_recurrent_booking', [
            'as' => 'ajax/cancel_recurrent_booking',
            'uses' => 'BookingController@cancel_recurrent_booking'
        ]);

        Route::post('cancel_one', [
            'as' => 'ajax/cancel_booking',
            'uses' => 'BookingController@cancel_booking'
        ]);

        Route::post('get_bookings_summary', [
            'as' => 'ajax/get_bookings_summary',
            'uses' => 'BookingController@bookings_summary',
        ]);

        Route::post('change_booking_player', [
            'as' => 'ajax/change_booking_player',
            'uses' => 'BookingController@change_booking_player'
        ]);

        Route::post('get_single_booking_details', [
            'as' => 'ajax/get_single_booking_details',
            'uses' => 'BookingController@single_booking_details'
        ]);

        Route::post('get_recurrent_bookings_list', [
            'as' => 'ajax/get_recurrent_bookings_list',
            'uses' => 'BookingController@get_recurrent_bookings_list'
        ]);

        Route::post('not_show_note_to_booking', [
            'as' => 'ajax/booking_not_show_change_status',
            'uses' => 'BookingController@not_show_status_change'
        ]);

        Route::post('make_default_profile', [
            'as'    => 'ajax/make_default_profile',
            'uses'  => 'FinancialProfiles@make_default_profile'
        ]);

        Route::post('add_friend_by_phone', [
            'as' => 'ajax/add_friend_by_phone',
            'uses' => 'FrontEndUserController@add_friend_by_phone'
        ]);

        Route::post('remove_friend_from_list', [
            'as' => 'ajax/remove_friend_from_list',
            'uses' => 'FrontEndUserController@remove_friend_from_list'
        ]);

        Route::post('approve_pending_friend', [
            'as' => 'ajax/approve_pending_friend',
            'uses' => 'FrontEndUserController@approve_pending_friend'
        ]);

        Route::post('get_friends_list', [
            'as' => 'ajax/get_friends_list',
            'uses' => 'FrontEndUserController@ajax_get_friends_list'
        ]);

        Route::post('ajax/finance_action_invoice_paid', [
            'as' => 'ajax/finance_action_invoice_paid',
            'uses' => 'InvoiceController@mark_as_paid'
        ]);

        Route::post('get_friends_players_list', [
            'as' => 'ajax/get_players_list',
            'uses' => 'FrontEndUserController@ajax_get_available_players_list'
        ]);

        Route::post('booking_action_player_show', [
            'as' => 'ajax/booking_action_player_show',
            'uses' => 'BookingController@booking_action_player_show',
        ]);

        Route::post('booking_action_invoice_paid', [
            'as' => 'ajax/booking_action_invoice_paid',
            'uses' => 'BookingController@booking_action_pay_invoice',
        ]);

        Route::post('simple_player_bookings_statistic', [
            'as' => 'ajax/simple_player_bookings_statistic',
            'uses' => 'BookingController@get_simple_player_statistics'
        ]);

        Route::post('calendar_booking_keep_selected', [
            'as' => 'ajax/calendar_booking_keep_selected',
            'uses' => 'BookingController@calendar_booking_keep_selected'
        ]);

        Route::post('calendar_booking_save_selected', [
            'as' => 'ajax/calendar_booking_save_selected',
            'uses' => 'BookingController@calendar_booking_save_selected'
        ]);

        Route::post('calendar_booking_move_selected', [
            'as' => 'ajax/calendar_booking_move_selected',
            'uses' => 'BookingController@calendar_booking_move_selected'
        ]);

        Route::post('calendar_booking_save_play_alone', [
            'as' => 'ajax/calendar_booking_save_play_alone',
            'uses' => 'BookingController@calendar_booking_save_play_alone'
        ]);

        Route::post('calendar_booking_save_recurring', [
            'as' => 'ajax/calendar_booking_save_recurring',
            'uses' => 'BookingController@calendar_booking_save_recurring'
        ]);

        Route::post('calendar_booking_save_recurring', [
            'as' => 'ajax/calendar_booking_save_recurring',
            'uses' => 'BookingController@calendar_booking_save_recurring'
        ]);

        Route::post('booking_membership_product_update', [
            'as' => 'ajax/booking_membership_product_update',
            'uses' => 'BookingController@update_booking_membership_product'
        ]);

        Route::post('validate_phone_number_for_registration', [
            'as' => 'ajax/check_phone_for_member_registration',
            'uses' => 'FrontEndUserController@validate_phone_for_member'
        ]);

        Route::post('validate_email_for_registration', [
            'as' => 'ajax/check_email_for_member_registration',
            'uses' => 'FrontEndUserController@validate_email_for_member'
        ]);

        Route::post('register_new_user_front', [
            'as' => 'ajax/register_new_member',
            'uses' => 'FrontEndUserController@new_member_registration'
        ]);

        Route::post('update_general_settings', [
            'as' => 'ajax/update_general_settings',
            'uses' => 'FrontEndUserController@update_general_settings'
        ]);

        Route::post('password_reset_request', [
            'as' => 'ajax/password_reset_request',
            'uses' => 'FrontEndUserController@password_reset_request'
        ]);

        Route::post('backend_password_reset_request',[
            'as'    => 'ajax/backend_password_reset_request',
            'uses'  => 'BackEndUserController@password_reset_request'
        ]);

        Route::post('general_note_add_new', [
            'as' => 'ajax/general_note_add_new',
            'uses' => 'GeneralNotesController@create'
        ]);

        Route::post('remove_member', [
            'as' => 'ajax/remove_member',
            'uses' => 'BackEndUserController@remove_member'
        ]);

        Route::post('back_member_change_status', [
            'as' => 'ajax/back_member_change_status',
            'uses' => 'BackEndUserController@activate_deactivate_member'
        ]);

        Route::post('internal_note_status_change', [
            'as' => 'ajax/internal_note_status_change',
            'uses' => 'GeneralNotesController@status_update'
        ]);

        Route::post('front_member_change_status', [
            'as' => 'ajax/front_member_change_status',
            'uses' => 'FrontEndUserController@change_account_status'
        ]);

        Route::post('reactivate_member', [
            'as' => 'ajax/reactivate_member',
            'uses' => 'BackEndUserController@reactivate_member'
        ]);

        Route::post('front_member_update_access_card', [
            'as' => 'ajax/front_member_update_access_card',
            'uses' => 'FrontEndUserController@update_access_card'
        ]);

        Route::post('register_new_setting', [
            'as' => 'ajax/register_new_setting',
            'uses' => 'AppSettings@register_new_setting'
        ]);

        Route::post('get_settings', [
            'as' => 'ajax/get_settings',
            'uses' => 'AppSettings@get_settings'
        ]);

        Route::post('get_items_settings', [
            'as' => 'ajax/get_items_settings',
            'uses' => 'AppSettings@get_items_settings'
        ]);

        Route::post('add_items_settings', [
            'as' => 'ajax/add_items_settings',
            'uses' => 'AppSettings@add_items_settings'
        ]);


        Route::post('update_settings', [
            'as' => 'ajax/update_settings',
            'uses' => 'AppSettings@update_settings'
        ]);

        Route::post('delete_settings', [
            'as' => 'ajax/delete_settings',
            'uses' => 'AppSettings@delete_settings'
        ]);

        Route::post('get_all_list_members', [
            'as' => 'ajax/get_all_list_members',
            'uses' => 'FrontEndUserController@get_front_members_ajax_call'
        ]);

        Route::post('buy_store_credit', [
            'as' => 'ajax/buy_store_credit',
            'uses' => 'FrontEndUserController@add_store_credit'
        ]);

        Route::post('auth_chek_email', [
            'as' => 'ajax/auth_chek_email',
            'uses' => 'FrontEndUserController@auth_chek_email'
        ]);

        Route::post('auth_check_password', [
            'as' => 'ajax/auth_check_password',
            'uses' => 'FrontEndUserController@auth_check_password'
        ]);

        Route::post('auth_autorize', [
            'as'    => 'ajax/auth_autorize',
            'uses'  => 'FrontEndUserController@auth_autorize'
        ]);

        Route::post('save_setting_application', [
            'as'    => 'ajax/save_setting_application',
            'uses'  => 'AppSettings@save_setting_application'
        ]);

        Route::post('save_allowed_setting', [
            'as'    => 'ajax/save_allowed_setting',
            'uses'  => 'AppSettings@save_allowed_setting'
        ]);

        Route::post('settings_clear_cached', [
            'as'    => 'ajax/settings_clear_cached',
            'uses'  => 'AppSettings@reset_application_cache_settings'
        ]);

        Route::post('location_set_activity_book_interval', [
            'as'    => 'ajax/location_set_activity_book_interval',
            'uses'  => 'ShopController@set_activity_time_interval'
        ]);

        Route::post('location_get_activity_book_interval', [
            'as'    => 'ajax/location_get_activity_book_interval',
            'uses'  => 'ShopController@get_activity_time_interval'
        ]);
        
        Route::post('get_resource_intervals_matrix', [
            'as'    => 'ajax/get_resource_intervals_matrix',
            'uses'  => 'AdminController@get_resource_intervals_matrix'
        ]);
        
        Route::post('get_activity_for_matrix', [
            'as'    => 'ajax/get_activity_for_matrix',
            'uses'  => 'AdminController@get_activity_for_matrix'
        ]);
    });

    Route::group(['middleware' => 'web', 'prefix' => 'optimize'], function () {
        Route::get('search_top_members', [
            'as' => 'optimize/search_top_members',
            'uses' => 'Optimizations@make_search_member_table_optimization'
        ]);

        Route::get('amend_search_top_members', [
            'as' => 'optimize/amend_search_top_members',
            'uses' => 'Optimizations@add_new_members_to_table'
        ]);
    });
}
