<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToOrdersTable extends Migration
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

        if (! Schema::hasColumn('orders', 'order_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('order_type')->default('default_type');
            });
        }
        if (! Schema::hasColumn('orders', 'extra_discount')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->float('extra_discount')->default(0);
            });
        }
        if (! Schema::hasColumn('orders', 'extra_discount_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('extra_discount_type')->nullable();
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
            $table->dropColumn('order_type');
            $table->dropColumn('extra_discount');
            $table->dropColumn('extra_discount_type');
        });
    }
}
