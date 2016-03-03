<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameFromToProductAvailability extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_availabilities', function($table) {
            $table->renameColumn('start_date' , 'available_from');
            $table->renameColumn('end_date'   , 'available_to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_availabilities', function($table) {
            $table->renameColumn('available_from', 'start_date');
            $table->renameColumn('available_to'  , 'end_date');
        });
    }
}
