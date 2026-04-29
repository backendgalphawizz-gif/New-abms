<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFourColumnToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        $cols = ['shipping_cost', 'multiply_qty', 'temp_shipping_cost', 'is_shipping_cost_updated'];
        foreach ($cols as $col) {
            if (! Schema::hasColumn('products', $col)) {
                Schema::table('products', function (Blueprint $table) use ($col) {
                    if ($col === 'multiply_qty' || $col === 'is_shipping_cost_updated') {
                        $table->boolean($col)->nullable();
                    } else {
                        $table->float($col)->nullable();
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('shipping_cost');
            $table->dropColumn('multiply_qty');
            $table->dropColumn('temp_shipping_cost');
            $table->dropColumn('is_shipping_cost_updated');
        });
    }
}
