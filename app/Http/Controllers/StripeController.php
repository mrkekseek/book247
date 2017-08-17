<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\StripeBilling;
use Auth;
use Carbon\Carbon;
use Config;
class StripeController extends Controller
{

    static public function chargeCustomer(Request $request)
    {
        Stripe::setApiKey(Config::get("stripe.stripe_secret"));
        $response = [
            'success' => FALSE
        ];

        $data = $request->only('token');
        if ($request->input("token"))
        {
            if ( ! self::isStripeCustomer())
            {
                $customer = self::createStripeCustomer($data["token"]);
            }
            else
            {
                $customer = Customer::retrieve(Auth::user()->stripe_id);
            }
            $response["success"] = TRUE;
        }

        $response['user'] = Auth::user();
        $response['user']->trial_month = Carbon::parse($response['user']->trial_ends_at)->format('m');
        $response['user']->trial_year = Carbon::parse($response['user']->trial_ends_at)->format('y');
        return $response;
    }

    static public function retrieveCustomer($stripe_id)
    {
        Stripe::setApiKey(Config::get("stripe.stripe_secret"));
        return Customer::retrieve($stripe_id);
    }


    static public function createStripeCharge($stripe_id, $amount = 50, $currency = "usd")
    {
        Stripe::setApiKey(Config::get("stripe.stripe_secret"));

        $charge = Charge::create(array(
            "amount" => $amount,
            "currency" => $currency,
            "customer" => $stripe_id
        ));
        return $charge;
    }

    static public function createStripeCustomer($token)
    {
        $customer = Customer::create([
            "description" => Auth::user()->email,
            "source" => $token
        ]);

        $card = end($customer->sources->data);
        User::where("id", "=", Auth::user()->id)->update(["stripe_id" => $customer->id, "card_brand" => strtolower($card->brand), "card_last_four" => $card->last4, "trial_ends_at" => Carbon::createFromFormat("m/d/Y", $card->exp_month . "/1/" . $card->exp_year)]);
        return $customer;
    }

    static public function isStripeCustomer()
    {
        return User::where('id', '=', Auth::user()->id)->where('stripe_id', '!=', '')->first();
    }
}
