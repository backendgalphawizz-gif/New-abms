<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublishToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        if (! Schema::hasColumn('products', 'request_status')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('request_status')->default(0);
            });
        }
        if (! Schema::hasColumn('products', 'denied_note')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('denied_note')->nullable();
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
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('request_status');
            $table->dropColumn('denied_note');
        });
    }
}
