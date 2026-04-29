<?php

use Illuminate\Database\Migrations\Migration;

class AddUserTypeToPasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // `user_type` is created in 2021_11_19_000000_create_password_resets_table.
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
