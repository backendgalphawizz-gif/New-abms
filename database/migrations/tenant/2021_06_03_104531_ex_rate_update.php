<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExRateUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('currencies')) {
            return;
        }

        if (! Schema::hasColumn('currencies', 'exchange_rate')) {
            Schema::table('currencies', function (Blueprint $table) {
                $table->string('exchange_rate')->default('1');
            });

            return;
        }

        Schema::table('currencies', function (Blueprint $table) {
            $table->string('exchange_rate')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currencies', function (Blueprint $table) {
            //
        });
    }
}
