<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SellerWalletWithdrawBal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('seller_wallets')) {
            return;
        }
        /** Baseline schema uses numeric columns; legacy string conversion skipped when `balance` is absent. */
        if (! Schema::hasColumn('seller_wallets', 'balance')) {
            return;
        }

        Schema::table('seller_wallets', function (Blueprint $table) {
            $table->string('withdrawn')->change();
            $table->string('balance')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seller_wallets', function (Blueprint $table) {
            //
        });
    }
}
