<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSellerIdToOrder extends Migration
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

        if (! Schema::hasColumn('orders', 'seller_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->bigInteger('seller_id')->nullable();
            });
        }
        if (! Schema::hasColumn('orders', 'seller_is')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('seller_is')->nullable();
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
            //
        });
    }
}
