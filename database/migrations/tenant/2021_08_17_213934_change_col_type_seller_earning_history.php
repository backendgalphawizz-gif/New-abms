<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColTypeSellerEarningHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('seller_wallet_histories')) {
            return;
        }

        if (! Schema::hasColumn('seller_wallet_histories', 'amount')) {
            return;
        }

        Schema::table('seller_wallet_histories', function (Blueprint $table) {
            $table->float('amount')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seller_wallet_histories', function (Blueprint $table) {
            //
        });
    }
}
