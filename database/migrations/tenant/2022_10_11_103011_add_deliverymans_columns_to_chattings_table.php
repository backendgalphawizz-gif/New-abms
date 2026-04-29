<?php

use Illuminate\Database\Migrations\Migration;

class AddDeliverymansColumnsToChattingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Columns are defined in 2022_10_10_000000_create_chattings_table; legacy alter + ->change() is not needed.
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
