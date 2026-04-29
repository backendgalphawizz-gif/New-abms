<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        if (! Schema::hasColumn('users', 'is_phone_verified')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_phone_verified')->default(0);
            });
        }

        if (! Schema::hasColumn('users', 'temporary_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('temporary_token')->nullable();
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
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
