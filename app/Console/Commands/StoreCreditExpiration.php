<?php

namespace App\Console\Commands;

use App\UserMembership;
use App\UserStoreCredits;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\User;

class StoreCreditExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store_credit_expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear pending memberships.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $members = DB::table('user_store_credits')
            ->select('member_id')
            ->groupBy('member_id')
            ->get();
        $users = array_map(function($object){
            return $object->member_id;
        },$members);
//        $users = [44];
        foreach ($users as $member) {
            $member = User::find($member);
            if ($member->get_available_store_credit()) {
                $credit_packs = UserStoreCredits::where('member_id',$member->id)->where('status','active')->orderBy('created_at','asc')->get();

                $payments = UserStoreCredits::where('member_id',$member->id)->where('value','<','0')->orderBy('created_at','asc')->get();
                foreach ($payments as $payment) {
                    $pay_date = Carbon::createFromFormat('Y-m-d H:i:s',$payment->updated_at);
                    $payment_made = 0;
                    $index = 0;
                    while (!$payment_made && $index < sizeof($credit_packs)) {
                        $expiration_date = Carbon::createFromFormat('Y-m-d',$credit_packs[$index]->expiration_date);
                        $start_date = Carbon::createFromFormat('Y-m-d H:i:s',$credit_packs[$index]->updated_at);
                        if ($pay_date->between($start_date,$expiration_date) && $credit_packs[$index]->value > 0) {
                            if ($credit_packs[$index]->value > -1 * $payment->value ) {
                                $credit_packs[$index]->value += $payment->value;
                                $payment->value = 0;
                                $payment_made = 1;
                            } else {
                                $credit_packs[$index]->value += $payment->value;
                                $payment->value = $credit_packs[$index]->value;
                                $credit_packs[$index]->value = 0;
                                $index++;
                            }
                        } else {
                            $index++;
                        }
                    }
                }
                $total_value = 0;
                $now = Carbon::today()->format('Y-m-d');
                foreach ($credit_packs as $key => $c_pack) {
                    $expiration_date = Carbon::createFromFormat('Y-m-d',$c_pack->expiration_date);
                    if ($now > $expiration_date) {
                        $total_value += $c_pack->value;
                    }
                }
                if ($total_value) {
                    $store_credit_fillable = [
                        'member_id'     => $member->id,
                        'back_user_id'  => $member->id,
                        'title'         => "Credit expired",
                        'value'         => intval(-$total_value),
                        'total_amount'  => $member->calculate_available_store_credit(),
                        'invoice_id'    => "",
                        'expiration_date'   => Carbon::today()->format('Y-m-d'),
                        'status'        => 'expired',
                    ];
                    UserStoreCredits::create($store_credit_fillable);
                    $member->update_available_store_credit();
                }

            }
        }
    }
}