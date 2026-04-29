<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentByAndPaymentNotToOrdersTable extends Migration
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

        if (! Schema::hasColumn('orders', 'payment_by')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('payment_by')->after('transaction_ref')->nullable();
            });
        }
        if (! Schema::hasColumn('orders', 'payment_note')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->text('payment_note')->after('payment_by')->nullable();
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

        if (Schema::hasColumn('orders', 'payment_by')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('payment_by');
            });
        }
        if (Schema::hasColumn('orders', 'payment_note')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('payment_note');
            });
        }
    }
}
