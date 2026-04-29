<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('admins')) {
            return;
        }

        if (Schema::hasColumn('admins', 'status')) {
            return;
        }

        Schema::table('admins', function (Blueprint $table) {
            $table->boolean('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasTable('admins') || ! Schema::hasColumn('admins', 'status')) {
            return;
        }

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
