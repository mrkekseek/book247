<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Auth;

class StripeController extends Controller
{
    /**
     * Charge a Stripe customer.
     *
     * @var Stripe\Customer $customer
     * @param integer $product_id
     * @param integer $product_price
     * @param string $product_name
     * @param string $token
     * @return createStripeCharge()
     */
     static public function chargeCustomer(Request $request)
     {
         Stripe::setApiKey(env('STRIPE_SECRET'));
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

         return $response;
     }

    /**
     * Create a Stripe charge.
     *
     * @var Stripe\Charge $charge
     * @var Stripe\Error\Card $e
     * @param integer $product_id
     * @param integer $product_price
     * @param string $product_name
     * @param Stripe\Customer $customer
     * @return postStoreOrder()
     */
     static public function createStripeCharge($customer, $amount = 50)
     {
         $charge = Charge::create(array(
             "amount" => $amount,
             "currency" => "usd",
             "customer" => $customer->id
         ));
         return $charge;
     }


    /**
     * Create a new Stripe customer for a given user.
     *
     * @var Stripe\Customer $customer
     * @param string $token
     * @return Stripe\Customer $customer
     */
     static public function createStripeCustomer($token)
     {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $customer = Customer::create([
            "description" => Auth::user()->email,
            "source" => $token
        ]);

        $card = end($customer->sources->data);
        User::where("id", "=", Auth::user()->id)->update(["stripe_id" => $customer->id, "card_brand" => strtolower($card->brand), "card_last_four" => $card->last4]);
        return $customer;
     }

    /**
     * Check if the Stripe customer exists.
     *
     * @return boolean
     */
     static public function isStripeCustomer()
     {
         return User::where('id', '=', Auth::user()->id)->where('stripe_id', '!=', '')->first();
     }
}
