<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * customer register/login views foreach social_login from Helpers::get_business_settings.
 */
class EnsureSocialLoginBusinessSetting extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('business_settings')) {
            return;
        }

        if (DB::table('business_settings')->where('type', 'social_login')->exists()) {
            return;
        }

        $now = now();
        DB::table('business_settings')->insert([
            'type' => 'social_login',
            'value' => json_encode([
                ['login_medium' => 'google', 'client_id' => '', 'client_secret' => '', 'status' => false],
                ['login_medium' => 'facebook', 'client_id' => '', 'client_secret' => '', 'status' => false],
            ]),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        // no-op
    }
}
