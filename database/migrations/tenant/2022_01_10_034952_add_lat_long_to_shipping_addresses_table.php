<?php

use Illuminate\Database\Migrations\Migration;

class AddLatLongToShippingAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // `shipping_addresses` is not created in this project’s tenant migrations — do not alter it.
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
