<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChattingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('chattings')) {
            return;
        }

        Schema::create('chattings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('seller_id')->nullable();
            $table->bigInteger('admin_id')->nullable();
            $table->bigInteger('delivery_man_id')->nullable();
            $table->text('message');
            $table->boolean('sent_by_customer')->default(0);
            $table->boolean('sent_by_seller')->default(0);
            $table->boolean('sent_by_admin')->nullable();
            $table->boolean('sent_by_delivery_man')->nullable();
            $table->boolean('seen_by_customer')->default(1);
            $table->boolean('seen_by_seller')->default(1);
            $table->boolean('seen_by_admin')->nullable();
            $table->boolean('seen_by_delivery_man')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->bigInteger('shop_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chattings');
    }
}
