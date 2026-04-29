<?php

use Illuminate\Database\Migrations\Migration;

class AddNotificationCountToNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // The `notifications` table is not created in this project’s tenant migrations — do not alter it.
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
