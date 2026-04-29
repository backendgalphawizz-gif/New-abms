<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingToOrdersTable extends Migration
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

        if (! Schema::hasColumn('orders', 'billing_address')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedBigInteger('billing_address')->nullable();
            });
        }
        if (! Schema::hasColumn('orders', 'billing_address_data')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->text('billing_address_data')->nullable();
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
            $table->dropColumn('billing_address');
            $table->dropColumn('billing_address_data');
        });
    }
}
