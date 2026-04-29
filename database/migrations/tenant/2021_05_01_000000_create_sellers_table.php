<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Baseline sellers (vendor) table — aligns with installation/backup/database_v13.1.sql
 * plus columns used by current registration code.
 */
class CreateSellersTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('sellers')) {
            return;
        }

        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->string('f_name', 30)->nullable();
            $table->string('l_name', 30)->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('image', 30)->default('def.png');
            $table->string('email', 80);
            $table->string('password', 80)->nullable();
            $table->string('status', 15)->default('pending');
            $table->string('otp', 32)->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->string('bank_name')->nullable();
            $table->string('branch')->nullable();
            $table->string('account_no')->nullable();
            $table->string('holder_name')->nullable();
            $table->string('account_type')->nullable();
            $table->string('micr_code')->nullable();
            $table->text('bank_address')->nullable();
            $table->string('ifsc_code')->nullable();

            $table->text('auth_token')->nullable();
            $table->double('sales_commission_percentage', 8, 2)->nullable();
            $table->string('gst')->nullable();
            $table->string('cm_firebase_token')->nullable();
            $table->boolean('pos_status')->default(false);

            $table->unique('email');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sellers');
    }
}
