<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Base tenant order line-items. Only ALTER migrations existed under tenant/, so fresh DBs failed.
 */
class CreateOrderDetailsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('order_details')) {
            return;
        }

        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->longText('product_details')->nullable();
            /** Snapshot JSON used by some APIs (see json_decode($order_details['product'], ...)). */
            $table->longText('product')->nullable();
            $table->integer('qty')->default(1);
            $table->double('price', 16, 2)->default(0);
            $table->double('tax', 16, 2)->default(0);
            $table->double('discount', 16, 2)->default(0);
            $table->string('tax_model', 20)->default('exclude');
            $table->string('discount_type')->nullable();
            $table->string('variant')->nullable();
            $table->longText('variation')->nullable();
            $table->string('delivery_status')->default('pending');
            $table->string('payment_status')->nullable();
            $table->string('shipping_method_id')->nullable();
            $table->unsignedBigInteger('shipping_address')->nullable();
            $table->boolean('is_stock_decreased')->default(true);
            $table->integer('refund_request')->default(0);
            $table->timestamps();

            $table->index('order_id');
            $table->index('product_id');
            $table->index('seller_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
