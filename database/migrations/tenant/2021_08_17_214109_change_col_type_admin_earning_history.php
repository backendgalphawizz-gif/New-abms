<?php

use Illuminate\Database\Migrations\Migration;

class ChangeColTypeAdminEarningHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Admin wallet histories are not used in this project — do not create or alter this table.
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
