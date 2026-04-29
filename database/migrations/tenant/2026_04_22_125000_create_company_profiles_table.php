<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CAB company records (linked from applications.company_id and users).
 */
class CreateCompanyProfilesTable extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('company_profiles')) {
            return;
        }

        Schema::create('company_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('application_id')->nullable()->index();
            $table->unsignedBigInteger('auditor_id')->nullable()->index();

            $table->string('name', 255)->nullable();
            $table->string('organization', 255)->nullable();
            $table->text('address')->nullable();
            $table->text('address_other_language')->nullable();
            $table->string('city', 191)->nullable();
            $table->string('country', 191)->nullable();
            $table->string('fax', 191)->nullable();
            $table->string('pincode', 64)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('phone', 64)->nullable();
            $table->string('email', 191)->nullable();

            $table->string('status', 64)->default('pending');
            $table->text('remark')->nullable();
            $table->string('ownership', 191)->nullable();

            $table->json('contact_person_details')->nullable();
            $table->json('parent_organization')->nullable();
            $table->json('invoice_address')->nullable();
            $table->json('ownership_details')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
}
