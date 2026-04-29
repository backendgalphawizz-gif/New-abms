<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToSellerWallet extends Migration
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

        $cols = [
            'commission_given',
            'total_earning',
            'pending_withdraw',
            'total_withdraw',
            'delivery_charge_earned',
            'collected_cash',
        ];
        foreach ($cols as $col) {
            if (! Schema::hasColumn('seller_wallets', $col)) {
                Schema::table('seller_wallets', function (Blueprint $table) use ($col) {
                    $table->float($col)->default(0);
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
        Schema::table('seller_wallets', function (Blueprint $table) {
            //
        });
    }
}
