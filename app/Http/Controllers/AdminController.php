<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Role;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\MessageBag;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $breadcrumbs = [
                'Home'      => route('admin'),
                'Dashboard' => '',
            ];
            $text_parts  = [
                'title'     => 'Home',
                'subtitle'  => 'users dashboard',
                'table_head_text1' => 'Dashboard Summary'
            ];
            $sidebar_link= 'admin-home_dashboard';

            return view('admin/main_page',[
                'breadcrumbs' => $breadcrumbs,
                'text_parts'  => $text_parts,
                'in_sidebar'  => $sidebar_link,
            ]);
        }
        else {
            return redirect()->intended(route('admin/login'));
        }
    }

    public function authenticate(Request $request)
    {
        if (Auth::attempt(['email' => $request->input('username'), 'password' => $request->input('password')])) {
            // Authentication passed...
            $user = Auth::user();
            if ($user->hasRole(['owner','manager'])){
                return redirect()->route('bookings/location_calendar_day_view',['day'=>\Carbon\Carbon::now()->format('d-m-Y')]);
            }
            else{
                return redirect()->intended('admin');
            }
        }
        else {
            $errors = new MessageBag([
                'password' => ['Username and/or password invalid.'],
                'username' => ['Username and/or password invalid.'],
                'header' => ['Invalid Login Attempt'],
                'message_body' => ['Username and/or password invalid.'],
            ]);

            return  redirect()->intended(route('admin/login'))
                    ->withInput()
                    ->withErrors($errors)
                    ->with('message', 'Login Failed');
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->intended(route('admin/login'));
    }
}
