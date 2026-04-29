<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Storefront home and Product queries use withCount('reviews'); tenant DBs had no create migration.
 */
class CreateReviewsTable extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('reviews')) {
            return;
        }

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable()->index();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('delivery_man_id')->nullable()->index();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->text('comment')->nullable();
            $table->longText('attachment')->nullable();
            $table->unsignedTinyInteger('rating')->default(0);
            $table->unsignedTinyInteger('status')->default(1)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
}
