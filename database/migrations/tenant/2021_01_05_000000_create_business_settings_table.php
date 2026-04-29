<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBusinessSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('business_settings')) {
            return;
        }

        Schema::create('business_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 50);
            $table->longText('value');
            $table->timestamps();
        });

        $now = now();

        DB::table('business_settings')->insert([
            [
                'type' => 'language',
                'value' => json_encode([
                    [
                        'id' => '1',
                        'name' => 'english',
                        'code' => 'en',
                        'status' => 1,
                        'default' => true,
                        'direction' => 'ltr',
                    ],
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'company_web_logo',
                'value' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'company_name',
                'value' => env('APP_NAME', 'CAB Management System'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'company_copyright_text',
                'value' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'seller_pos',
                'value' => '0',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'colors',
                'value' => json_encode([
                    'primary' => '#1b7fed',
                    'secondary' => '#000000',
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'recaptcha',
                'value' => json_encode([
                    'status' => 0,
                    'site_key' => '',
                    'secret_key' => '',
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'system_default_currency',
                'value' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'seller_registration',
                'value' => '0',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'social_login',
                'value' => json_encode([
                    ['login_medium' => 'google', 'client_id' => '', 'client_secret' => '', 'status' => false],
                    ['login_medium' => 'facebook', 'client_id' => '', 'client_secret' => '', 'status' => false],
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type' => 'company_fav_icon',
                'value' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
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
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_settings');
    }
}
