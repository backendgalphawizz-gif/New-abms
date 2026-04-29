<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCouponDiscountBearerAndAdminCommissionToOrders extends Migration
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

        if (! Schema::hasColumn('orders', 'coupon_discount_bearer')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('coupon_discount_bearer')->after('coupon_code')->default('inhouse');
            });
        }
        if (! Schema::hasColumn('orders', 'admin_commission')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('admin_commission')->after('order_amount')->default(0);
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
        if (! Schema::hasTable('orders')) {
            return;
        }

        if (Schema::hasColumn('orders', 'coupon_discount_bearer')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('coupon_discount_bearer');
            });
        }
        if (Schema::hasColumn('orders', 'admin_commission')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('admin_commission');
            });
        }
    }
}
