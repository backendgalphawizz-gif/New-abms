<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Baseline shops table — dump v13.1 + fields used by seller registration.
 */
class CreateShopsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('shops')) {
            return;
        }

        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->string('name', 100);
            $table->string('address', 255)->default('');
            $table->string('contact', 25)->default('');
            $table->string('email', 80)->nullable();
            $table->string('image', 30)->default('def.png');
            $table->string('banner', 191)->default('def.png');
            $table->string('bottom_banner', 191)->nullable();
            $table->date('vacation_start_date')->nullable();
            $table->date('vacation_end_date')->nullable();
            $table->string('vacation_note', 255)->nullable();
            $table->tinyInteger('vacation_status')->default(0);
            $table->tinyInteger('temporary_close')->default(0);
            $table->string('bussiness_type')->nullable();
            $table->string('registeration_number')->nullable();
            $table->string('gst_in')->nullable();
            $table->string('tax_identification_number')->nullable();
            $table->string('website_link')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('country')->nullable();
            $table->string('area')->nullable();
            $table->string('refferral')->nullable();
            $table->string('friends_code')->nullable();
            $table->timestamps();

            $table->index('seller_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
