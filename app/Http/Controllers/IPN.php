<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Invoice;
use App\InvoiceFinancialTransaction;
use App\Paypal;
use App\UserMembership;
use App\UserMembershipInvoicePlanning;

class IPN extends Controller{


    public function membership_ipn(Request $r){
        $d = json_decode(json_encode($r->all()),true);
        $req = 'cmd=_notify-validate';

        foreach ($d as $key => $value) {
            $value = urlencode($value);
            $req .= "&$key=$value";
        }

        $ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        if ( !($res = curl_exec($ch)) ) {
            echo "Got " . curl_error($ch) . " when processing IPN data";
            curl_close($ch);
            return ;
        }
        curl_close($ch);

        if ($res != 'VERIFIED') {
            return ;
        }

        $invoice = Invoice::find($r->get('invoice'));
        if (!$invoice){
            // log this error with all post/get variables
            exit;
        }

        $transaction = InvoiceFinancialTransaction::where(
            [
                ['invoice_id','=',$invoice->id],
                ['other_details','=',$r->get('txn_id')]
            ]
        )->first();
        if (!$transaction){
            // log error and return
            exit;
        }

        $log = new Paypal();
        $log->fill([
            'invoice_id' => $invoice->id,
            'transaction_id' => $transaction->id,
            'paypal_response' => json_encode($r->all())
        ])->save();

        switch ($invoice->invoice_type){
            case 'booking_invoice' :

                break;
            case 'membership_plan_assignment_invoice' :
            case 'membership_plan_invoice' :
                $invoicePlan = UserMembershipInvoicePlanning::where('invoice_id',$invoice->id)->first();
                $membership = UserMembership::find($invoicePlan->user_membership_id);

                // fix
                switch ($r->get('payment_status')) {
                    case 'Completed':
                        $invoice->status = 'completed';
                        $invoice->save();
                        $transaction->status = 'completed';
                        $transaction->save();
                        $membership->status = 'active';
                        $membership->save();
                        break;
                    case 'Processed' || 'Pending' :
                        $invoice->status = 'pending';
                        $invoice->save();
                        $transaction->status = 'pending';
                        $transaction->save();
                        $membership->status = 'active';
                        $membership->save();
                        break;
                    case 'Pending' :
                        $invoice->status = 'pending';
                        $invoice->save();
                        $transaction->status = 'pending';
                        $transaction->save();
                        $membership->status = 'active';
                        $membership->save();
                        break;
                    default:
                        $invoice->status = 'pending';
                        $invoice->save();
                        $transaction->status = 'cancelled';
                        $transaction->save();
                        $membership->status = 'pending';
                        $membership->save();
                }
                break;
            case 'store_credit' :
                // this is the result of a cancellation where store credit is returned

                break;
            case 'store_credit_invoice' :
                // this is the result of buying store credit from frontend or backend, added by the admin/employees

                break;
            default:

                break;
        }
    }

    public function membership_paypal_success(Request $r)
    {
        $amount = $r->get('amt');
        $currency = $r->get('cc');
        $transactionId = $r->get('tx');
        $url = $r->get('cm');

        $breadcrumbs = [
            'Home'      => route('admin'),
            'Dashboard' => '',
        ];
        $text_parts  = [
            'title'     => 'Home',
            'subtitle'  => 'users dashboard',
            'table_head_text1' => 'Dashboard Summary'
        ];
        $sidebar_link= 'front-homepage';

        $key = env('APP_KEY') ;
        if($key){
            while(strlen($key) < 16){
                $key .= env('APP_KEY');
            }
            $key = substr($key,0,16);
        }
        $iv = env('APP_KEY');
        if($iv){
            while(strlen($iv) < 16){
                $iv .= env('APP_KEY');
            }
            $iv = substr($key,0,16);
        }
        $custom = json_decode(openssl_decrypt(base64_decode($url),'AES-256-CBC',$key,0 ,$iv));
        if (!isset($custom->invoice_id)) {
            return view('front/iframe/federation/success',[
                'breadcrumbs' => $breadcrumbs,
                'text_parts'  => $text_parts,
                'in_sidebar'  => $sidebar_link,
                'text' => 'Payment successful!',
                'status' => 'Success',
                'link' => ' '
            ]);
        }
        $invoice = Invoice::find($custom->invoice_id);
        if ($invoice) {
            $transaction = new InvoiceFinancialTransaction();
            $transaction->fill([
                'invoice_id' => $invoice->id,
                'user_id' => $invoice->user_id,
                'transaction_amount' => $amount,
                'transaction_currency' => $currency,
                'transaction_type' => 'paypal',
                'status' => 'pending',
                'other_details' => $transactionId
            ])->save();

            switch ($invoice->invoice_type){
                case 'booking_invoice' :
                    $invoice->status = 'processing';
                    $invoice->save();
                    break;
                case 'membership_plan_assignment_invoice' :
                case 'membership_plan_invoice' :
                    $invoice_plan = UserMembershipInvoicePlanning::where('invoice_id',$custom->invoice_id)->first();
                    $user_membership = UserMembership::find($invoice_plan->user_membership_id);

                    $invoice->status = 'processing';
                    $invoice->save();
                    $user_membership->status = 'active';
                    $user_membership->save();
                    break;
                case 'store_credit' :
                    // this is the result of a cancellation where store credit is returned
                    $invoice->status = 'processing';
                    $invoice->save();
                    break;
                case 'store_credit_invoice' :
                    // this is the result of buying store credit from frontend or backend, added by the admin/employees
                    $invoice->status = 'processing';
                    $invoice->save();
                    break;
                default:
                    $invoice->status = 'processing';
                    $invoice->save();
                    break;
            }
        }

        return view('front/iframe/federation/success',[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'status' => 'Success',
            'link' => $custom->redirect_url
        ]);
    }
    
    public function membership_paypal_cancel(Request $request){

    }

}