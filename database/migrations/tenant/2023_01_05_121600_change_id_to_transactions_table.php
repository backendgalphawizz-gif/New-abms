<?php

use Illuminate\Database\Migrations\Migration;

class ChangeIdToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // The `transactions` table is not used in this project — do not create or alter it.
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
