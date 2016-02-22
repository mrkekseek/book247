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

Route::get('/', function () {
    echo "Homepage default";
});

Route::get('/roles', [
        'uses'  => 'User@roles'
    ]);

Route::group(['middleware' => 'web'], function () {
    // default login/register/forgot password routes
    Route::auth();

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

    Route::get('/admin/text-auth', 'HomeController@index');

    Route::get('/admin', [
        'as'    => 'admin',
        'uses'  => 'AdminController@index'
    ]);

    Route::get('/admin/back_users/', [
        'as'    =>  'admin/back_users',
        'uses'  =>  'BackEndUserController@index'
    ]);

    Route::put('/admin/back_users/add_user', [
        'as'    =>  'admin/back_users/add_user',
        'uses'  =>  'BackEndUserController@create'
    ]);

    Route::get('/admin/back_users/user_roles', [
        'as'    =>  'admin/back_users/user_roles',
        'uses'  =>  'BackEndUserController@all_users_roles'
    ]);

    Route::post('/admin/back_users/user_roles', [
        'as'    =>  'admin/back_users/user_roles',
        'uses'  =>  'BackEndUserController@add_user_role'
    ]);

    Route::put('/admin/back_users/user_roles', [
        'as'    =>  'admin/back_users/user_roles',
        'uses'  =>  'BackEndUserController@update_user_role'
    ]);

    Route::delete('/admin/back_users/user_roles', [
        'as'    =>  'admin/back_users/user_roles',
        'uses'  =>  'BackEndUserController@delete_user_role'
    ]);

    Route::get('/admin/back_users/view_user/{id}', [
        'as' => 'admin/back_users/view_user/',
        'uses' => 'BackEndUserController@show',
    ]);

    Route::post('admin/back_users/view_user/{id}/acc_info', [
        'as' => 'admin/back_users/view_user/acc_info',
        'uses' => 'BackEndUserController@update_account_info',
    ]);
});
