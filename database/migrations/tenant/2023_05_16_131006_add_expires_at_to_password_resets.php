<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiresAtToPasswordResets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('password_resets')) {
            return;
        }

        Schema::table('password_resets', function (Blueprint $table) {
            if (! Schema::hasColumn('password_resets', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('token');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasTable('password_resets') || ! Schema::hasColumn('password_resets', 'expires_at')) {
            return;
        }

        Schema::table('password_resets', function (Blueprint $table) {
            $table->dropColumn('expires_at');
        });
    }
}
