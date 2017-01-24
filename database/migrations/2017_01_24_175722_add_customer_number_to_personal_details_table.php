<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerNumberToPersonalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_details', function (Blueprint $table) {
            $table->char('customer_number', 50)->nullable()->after('bank_acc_no');
        });

        $personal_details = \App\PersonalDetail::all();
        $customer_number = 10001;
        if ($personal_details){
            foreach($personal_details as $single){
                $single->customer_number = $customer_number++;
                $single->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_details', function (Blueprint $table) {
            $table->dropColumn('customer_number');
        });
    }
}
