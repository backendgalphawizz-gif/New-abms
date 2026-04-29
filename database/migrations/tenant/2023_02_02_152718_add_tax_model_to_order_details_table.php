<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxModelToOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('order_details') || Schema::hasColumn('order_details', 'tax_model')) {
            return;
        }

        Schema::table('order_details', function (Blueprint $table) {
            $table->string('tax_model', 20)->after('discount')->default('exclude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasTable('order_details') || ! Schema::hasColumn('order_details', 'tax_model')) {
            return;
        }

        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('tax_model');
        });
    }
}
