<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Product categories — matches installation/backup/database_v13.1.sql
 * (includes home_status + priority so later ALTERs can no-op).
 */
class CreateCategoriesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('categories')) {
            return;
        }

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->string('icon', 250)->nullable();
            $table->integer('parent_id')->default(0);
            $table->integer('position')->default(0);
            $table->boolean('home_status')->default(false);
            $table->integer('priority')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
