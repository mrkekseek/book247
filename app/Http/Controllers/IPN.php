<?php
namespace App\Http\Controllers;
use App\StoreCreditProducts;
use App\UserStoreCredits;
use Illuminate\Http\Request;
use App\Invoice;
use App\InvoiceItem;
use App\User;
use App\PersonalDetail;
use Snowfire\Beautymail\Beautymail;
use App\InvoiceFinancialTransaction;
use App\Paypal;
use App\UserMembership;
use App\UserMembershipInvoicePlanning;
use Auth;

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
            case 'store_credit_pack_invoice' :


//                switch ($r->get('payment_status')) {
//                    case 'Completed':
//                        $invoice->status = 'completed';
//                        $invoice->save();
//                        $invoice_item = InvoiceItem::where('invoice_id',$invoice->id)->first();
//                        if($invoice_item->item_type == 'store_credit_pack') {
//                            $pack = StoreCreditProducts::find($invoice_item->item_reference_id);
//                            $user = User::find($invoice->user_id);
//                            $status = $user->trusted_add_store_credit($invoice->id, $pack->store_credit_value, $pack->valid_to);
//                        }
//                        break;
//                    case 'Processed' || 'Pending' :
//                        $invoice->status = 'pending';
//                        $invoice->save();
//                        break;
//                    case 'Pending' :
//                        $invoice->status = 'pending';
//                        $invoice->save();
//                        break;
//                    default:
//                        $invoice->status = 'pending';
//                        $invoice->save();
//                }

            default:

                break;
        }
    }

    public function membership_paypal_success(Request $r)
    {
        session_start();
        $vars = $r->all();

        if (isset($vars['mc_gross'])){
            // we're on POST method
            $amount = $vars['mc_gross'];
            $currency = $vars['mc_currency'];
            $transactionId = $vars['txn_id'];
            $url = $vars['custom'];
        }
        else if(isset($vars['amt'])){
            // we're on GET method
            $amount = $vars['amt'];
            $currency = $vars['cc'];
            $transactionId = $vars['tx'];
            $url = $vars['cm'];
        }

        if (env('FEDERATION',false)){
            $blade = 'front/iframe/federation/success';
            $link = route('homepage');
        }
        else{
            $blade = 'front/iframe/success';
            $link = route('homepage');
        }

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

        if (isset($amount) && isset($currency) && isset($transactionId) && isset($url)){
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
                return view($blade,[
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
                $outstandingAmount = $invoice->get_outstanding_amount();
                $unpaidItems = $invoice->get_unpaid_invoice_items();
                if (number_format($outstandingAmount,2) == number_format($amount,2)){
                    // amount is correct
                }
                else{
                    return view($blade,[
                        'breadcrumbs' => $breadcrumbs,
                        'text_parts'  => $text_parts,
                        'in_sidebar'  => $sidebar_link,
                        'text' => 'Payment successful!',
                        'status' => 'Success',
                        'link' => $custom->redirect_url
                    ]);
                }

                $transaction->fill([
                    'invoice_id' => $invoice->id,
                    'user_id' => $invoice->user_id,
                    'invoice_items' => json_encode($unpaidItems),
                    'transaction_amount' => $amount,
                    'transaction_currency' => $currency,
                    'transaction_type' => 'paypal',
                    'transaction_date' => date('Y-m-d'),
                    'status' => 'pending',
                    'other_details' => $transactionId
                ])->save();


                self::send_mail($invoice->id);
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
                        $credits = UserStoreCredits::find($invoice->invoice_reference_id);
                        $credits->status = 'active';
                        $credits->save();
                        break;
                        break;
                    default:
                        $invoice->status = 'processing';
                        $invoice->save();
                        break;
                }

                $log = new Paypal();
                $log->fill([
                    'invoice_id' => $invoice->id,
                    'transaction_id' => $transaction->id,
                    'paypal_response' => json_encode($r->all())
                ])->save();
            }
            self::send_mail($invoice->id);
            $status = "Success";
        }
        else{
            // we got a direct access to the page
            $status = "Unknown";

        }

        return view($blade,[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'status'    => $status,
            'link'      => isset($custom->redirect_url)?$custom->redirect_url:$link
        ]);
    }

    public static function send_mail($invoice_id, $status = true)
    {
        $invoice = Invoice::find($invoice_id);
        $invoice_item = InvoiceItem::where('invoice_id',$invoice->id)->first();
        $user = User::find($invoice->user_id);
        $personalDetails = PersonalDetail::where('user_id',$user->id)->first();
        $data = [
            'first_name'            => $user->first_name,
            'last_name'             => $user->last_name,
            'middle_name'           => $user->middle_name,
            'username'              => $user->username,
            'product'               => $invoice_item->item_name
        ];

        if ($status) {

            $default_message = '<p>Thank you for '.($invoice->invoice_type == 'booking_invoice' ? 'booking' : 'purchasing').' [[product]].</p>';
            $default_subject = 'Your '.( $invoice->invoice_type == 'booking_invoice' ? 'booking' : 'purchase') . ' is successful!';
            $template = EmailsController::build('Purchase successful', $data, $default_message, $default_subject);

            $main_message = $template["message"];
            $subject = AppSettings::get_setting_value_by_name('globalWebsite_email_company_name_in_title') ?
                AppSettings::get_setting_value_by_name('globalWebsite_email_company_name_in_title') . ' - '.$template['subject'] :
                $template['subject'];

        } else {
            // not successful
            $default_message = '<p>The '.($invoice->invoice_type == 'booking_invoice' ? 'booking' : 'purchasing').' of [[product]] was unsuccessful.</p>';
            $default_subject = 'Your '.( $invoice->invoice_type == 'booking_invoice' ? 'booking' : 'purchase' ). ' is unsuccessful!';
            $template = EmailsController::build('Purchase unsuccessful', $data, $default_message, $default_subject);

            $main_message = $template["message"];
            $subject = AppSettings::get_setting_value_by_name('globalWebsite_email_company_name_in_title') ?
                AppSettings::get_setting_value_by_name('globalWebsite_email_company_name_in_title') . ' - '.$template['subject'] :
                $template['subject'];

        }

        $beauty_mail = app()->make(Beautymail::class);
        $beauty_mail->send('emails.email_default_v2',
            ['body_message' => $main_message, 'user' => $user],
            function($message) use ($user, $subject) {
                $message
                    ->from(AppSettings::get_setting_value_by_name('globalWebsite_system_email'))
                    ->to($user->email, $user->first_name.' '.$user->middle_name.' '.$user->last_name)
                    ->subject($subject);
            });
    }

    public function membership_paypal_cancel(Request $request){

        if (env('FEDERATION',false)){
            $blade = 'front/iframe/federation/success';
            $link = route('homepage');
        }
        else{
            $blade = 'front/iframe/success';
            $link = route('homepage');
        }
        $link = '';
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
        $vars = $request->all();
        if (isset($vars['custom'])) $url = $vars['custom'];

        $status = 'Canceled';

        if(isset($url)) {
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
            if (isset($custom->invoice_id)) {
                self::send_mail($custom->invoice_id,false);
            }
        }

        return view($blade,[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'status'    => $status,
            'link'      => isset($custom->redirect_url)?$custom->redirect_url:$link
        ]);
    }

}