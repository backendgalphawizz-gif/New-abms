<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * AppServiceProvider expects app_home_page (gift_section / top_deal_product) and fav icon row.
 */
class EnsureAppHomePageAndFaviconBusinessSettings extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('business_settings')) {
            return;
        }

        $now = now();

        if (!DB::table('business_settings')->where('type', 'app_home_page')->exists()) {
            DB::table('business_settings')->insert([
                'type' => 'app_home_page',
                'value' => json_encode([
                    'frame_one' => ['label_1' => '', 'label_2' => '', 'label_3' => ''],
                    'top_deal_product' => ['title' => '', 'product_ids' => ''],
                    'gift_section' => ['gift_title' => '', 'gift_product_ids' => ''],
                    'summer_sale_banner' => ['image' => '', 'discount_percent' => ''],
                    'prime_time_banner' => ['image' => ''],
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        if (!DB::table('business_settings')->where('type', 'company_fav_icon')->exists()) {
            DB::table('business_settings')->insert([
                'type' => 'company_fav_icon',
                'value' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        //
    }
}
