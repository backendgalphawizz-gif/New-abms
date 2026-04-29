<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Baseline seller_wallets — matches database_v13.1.sql final shape so legacy
 * rename/drop migrations can be skipped safely.
 */
class CreateSellerWalletsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('seller_wallets')) {
            return;
        }

        Schema::create('seller_wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->double('total_earning')->default(0);
            $table->double('withdrawn')->default(0);
            $table->timestamps();
            $table->double('commission_given', 8, 2)->default(0);
            $table->double('pending_withdraw', 8, 2)->default(0);
            $table->double('delivery_charge_earned', 8, 2)->default(0);
            $table->double('collected_cash', 8, 2)->default(0);
            $table->double('total_tax_collected', 8, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('seller_wallets');
    }
}
