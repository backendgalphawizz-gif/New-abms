<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('assessors')) {
            return;
        }

        Schema::create('assessors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('assessor_id')->nullable()->index();
            $table->string('apply_designation', 191)->nullable();
            $table->string('highest_qualification', 191)->nullable();
            $table->string('technical_area', 191)->nullable();
            $table->integer('experience')->default(0);
            $table->text('home_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assessors');
    }
}

