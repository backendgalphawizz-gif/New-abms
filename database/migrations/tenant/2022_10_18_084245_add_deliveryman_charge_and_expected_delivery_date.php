<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliverymanChargeAndExpectedDeliveryDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        if (! Schema::hasColumn('orders', 'deliveryman_charge')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->double('deliveryman_charge', 50)->default(0)->after('delivery_man_id');
            });
        }
        if (! Schema::hasColumn('orders', 'expected_delivery_date')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->date('expected_delivery_date')->nullable()->after('deliveryman_charge');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('deliveryman_charge');
            $table->dropColumn('expected_delivery_date');
        });
    }
}
