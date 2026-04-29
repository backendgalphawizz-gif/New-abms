<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * default_theme() and wishlist views expect product_brand in business_settings.
 */
class EnsureProductBrandBusinessSetting extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('business_settings')) {
            return;
        }

        if (DB::table('business_settings')->where('type', 'product_brand')->exists()) {
            return;
        }

        $now = now();
        DB::table('business_settings')->insert([
            'type' => 'product_brand',
            'value' => '0',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        // no-op
    }
}
