<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeoColumnsToProducts extends Migration
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

        if (! Schema::hasColumn('products', 'meta_title')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('meta_title')->nullable();
            });
        }
        if (! Schema::hasColumn('products', 'meta_description')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('meta_description')->nullable();
            });
        }
        if (! Schema::hasColumn('products', 'meta_image')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('meta_image')->nullable();
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
            //
        });
    }
}
