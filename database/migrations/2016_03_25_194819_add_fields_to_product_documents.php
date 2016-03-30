<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProductDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_documents', function (Blueprint $table) {
            $table->dropColumn('local_path');
            $table->string('name', 150)->change();
            $table->renameColumn('name', 'file_name');
            $table->string('file_location');
            $table->string('file_type');
            $table->string('label');
            $table->string('category');
            $table->text('comments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
