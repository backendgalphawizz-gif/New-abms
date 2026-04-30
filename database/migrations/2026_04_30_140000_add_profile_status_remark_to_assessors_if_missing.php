<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileStatusRemarkToAssessorsIfMissing extends Migration
{
    /**
     * Run the migrations.
     * profile_status: 0 = pending, 1 = approved, 2 = rejected (aligned with admin employee UI).
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('assessors')) {
            return;
        }

        Schema::table('assessors', function (Blueprint $table) {
            if (!Schema::hasColumn('assessors', 'profile_status')) {
                $table->unsignedTinyInteger('profile_status')->default(0);
            }
            if (!Schema::hasColumn('assessors', 'remark')) {
                $table->text('remark')->nullable();
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
        if (!Schema::hasTable('assessors')) {
            return;
        }

        Schema::table('assessors', function (Blueprint $table) {
            if (Schema::hasColumn('assessors', 'remark')) {
                $table->dropColumn('remark');
            }
            if (Schema::hasColumn('assessors', 'profile_status')) {
                $table->dropColumn('profile_status');
            }
        });
    }
}
