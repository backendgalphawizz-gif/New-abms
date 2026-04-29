<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Base tenant users schema. Historical "create users" was never copied into
 * database/migrations/tenant; only ALTER migrations existed, so fresh tenant DBs failed.
 */
class CreateUsersTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('f_name')->nullable();
            $table->string('l_name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('company_name')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('area')->nullable();
            $table->string('zip')->nullable();
            $table->text('street_address')->nullable();
            $table->string('shop_name')->nullable();
            $table->string('age')->nullable();
            $table->string('gender', 32)->nullable();
            $table->string('image')->nullable();
            $table->string('referral_code')->nullable();
            $table->string('friends_code')->nullable();
            $table->string('otp', 32)->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
