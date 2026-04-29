<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBottomBannerToShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('shops') || Schema::hasColumn('shops', 'bottom_banner')) {
            return;
        }

        Schema::table('shops', function (Blueprint $table) {
            $table->string('bottom_banner')->after('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasTable('shops') || ! Schema::hasColumn('shops', 'bottom_banner')) {
            return;
        }

        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('bottom_banner');
        });
    }
}
