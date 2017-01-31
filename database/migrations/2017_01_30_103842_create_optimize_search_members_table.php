<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptimizeSearchMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('optimize_search_members', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('user_id');
            $table->char('first_name', 100);
            $table->char('middle_name', 100)->nullable();
            $table->char('last_name', 100);

            $table->char('first_last_name', 255);
            $table->char('first_middle_last_name', 255);

            $table->char('email', 255);
            $table->char('phone', 30);

            $table->char('city', 30)->nullable();
            $table->char('region', 30)->nullable();

            $table->char('membership_name', 75);
            $table->char('user_profile_image', 255);

            $table->char('user_link_details', 255);

            $table->timestamps();
        });

        DB::statement("ALTER TABLE `optimize_search_members` ADD FULLTEXT (`first_name`);");
        DB::statement("ALTER TABLE `optimize_search_members` ADD FULLTEXT (`middle_name`);");
        DB::statement("ALTER TABLE `optimize_search_members` ADD FULLTEXT (`last_name`);");
        DB::statement("ALTER TABLE `optimize_search_members` ADD FULLTEXT (`first_last_name`);");
        DB::statement("ALTER TABLE `optimize_search_members` ADD FULLTEXT (`first_middle_last_name`);");

        DB::statement("ALTER TABLE `optimize_search_members` ADD FULLTEXT (`email`);");

        DB::statement("ALTER TABLE `optimize_search_members` ADD FULLTEXT (`phone`);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('optimize_search_members');
    }
}
