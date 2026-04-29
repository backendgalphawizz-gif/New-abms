<?php

use Illuminate\Database\Migrations\Migration;

class ChangePassResetEmailCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Legacy rename email → identity; new installs use 2021_11_19_000000_create_password_resets_table with `identity` already.
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
