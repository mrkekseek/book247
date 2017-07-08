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
        $transaction = InvoiceFinancialTransaction::where(
            [
                ['invoice_id','=',$invoice->id],
                ['other_details','=',$r->get('txn_id')]

            ]
        )->first();

        $log = new Paypal();
        $log->fill([
            'invoice_id' => $invoice->id,
            'transaction_id' => $transaction->id,
            'paypal_response' => json_encode($r->all())
        ])->save();

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


    }


}