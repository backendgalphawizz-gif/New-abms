<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Baseline products catalog — matches installation/backup/database_v13.1.sql.
 * Required before 2021_06_04_195853_product_dis_tax (Doctrine ->change() on missing columns).
 */
class CreateProductsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('products')) {
            return;
        }

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('added_by')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name', 80)->nullable();
            $table->string('slug', 120)->nullable();
            $table->string('product_type', 20)->default('physical');
            $table->string('category_ids', 80)->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->string('unit')->nullable();
            $table->integer('min_qty')->default(1);
            $table->boolean('refundable')->default(true);
            $table->string('digital_product_type', 30)->nullable();
            $table->string('digital_file_ready')->nullable();
            $table->longText('images')->nullable();
            $table->text('color_image')->default('');
            $table->string('thumbnail')->nullable();
            $table->string('featured')->nullable();
            $table->string('flash_deal')->nullable();
            $table->string('video_provider', 30)->nullable();
            $table->string('video_url', 150)->nullable();
            $table->string('colors', 150)->nullable();
            $table->boolean('variant_product')->default(false);
            $table->string('attributes')->nullable();
            $table->text('choice_options')->nullable();
            $table->text('variation')->nullable();
            $table->boolean('published')->default(false);
            $table->double('unit_price')->default(0);
            $table->double('purchase_price')->default(0);
            $table->string('tax')->default('0.00');
            $table->string('tax_type', 80)->nullable();
            $table->string('tax_model', 20)->default('exclude');
            $table->string('discount')->default('0.00');
            $table->string('discount_type', 80)->nullable();
            $table->integer('current_stock')->nullable();
            $table->integer('minimum_order_qty')->default(1);
            $table->text('details')->nullable();
            $table->boolean('free_shipping')->default(false);
            $table->string('attachment')->nullable();
            $table->timestamps();
            $table->boolean('status')->default(true);
            $table->boolean('featured_status')->default(true);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_image')->nullable();
            $table->boolean('request_status')->default(false);
            $table->string('denied_note')->nullable();
            $table->double('shipping_cost', 8, 2)->nullable();
            $table->boolean('multiply_qty')->nullable();
            $table->double('temp_shipping_cost', 8, 2)->nullable();
            $table->boolean('is_shipping_cost_updated')->nullable();
            $table->string('code')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
