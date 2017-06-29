<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webpatser\Countries\Countries;
use App\Http\Requests;


class RegistrationStepsController extends Controller
{
   	public function registrationSteps()
   	{
		$countries = Countries::orderBy('name', 'asc')->get();
		$currencies = Countries::groupBy('currency_code')->get();
		return view('registration-form', ['countries'=>$countries, 'currencies'=>$currencies]);
   	}

}
