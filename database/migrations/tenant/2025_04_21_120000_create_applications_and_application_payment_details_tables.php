<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ABMS accreditation flows use these tables on the tenant connection.
 * They were missing from tenant migrations, so /admin dashboard failed after login.
 */
class CreateApplicationsAndApplicationPaymentDetailsTables extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('applications')) {
            Schema::create('applications', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('reference_number', 191)->nullable()->index();
                $table->string('application_type', 191)->nullable();
                $table->unsignedBigInteger('scheme_id')->nullable()->index();
                $table->text('area_ids')->nullable();

                $table->string('status', 64)->default('pending');
                $table->string('application_status', 64)->default('pending');
                $table->unsignedTinyInteger('declaration')->default(1);
                $table->json('application_outside_usa')->nullable();
                $table->text('application_outside_usa_text')->nullable();
                $table->unsignedTinyInteger('is_accept')->default(0);
                $table->text('signature')->nullable();

                $table->unsignedBigInteger('auditor_id')->nullable();
                $table->text('auditor_team_ids')->nullable();
                $table->unsignedTinyInteger('auditor_status')->nullable();
                $table->unsignedTinyInteger('client_auditor_team_status')->nullable();

                $table->unsignedBigInteger('office_assessment_id')->nullable();
                $table->text('office_assessment_team_ids')->nullable();
                $table->unsignedTinyInteger('office_assessment_status')->nullable();
                $table->unsignedTinyInteger('client_office_assessment_team_status')->nullable();
                $table->text('client_office_assessment_team_remark')->nullable();

                $table->unsignedBigInteger('witness_assessment_id')->nullable();
                $table->text('witness_assessment_team_ids')->nullable();
                $table->unsignedTinyInteger('witness_assessment_status')->nullable();
                $table->unsignedTinyInteger('client_witness_assessment_team_status')->nullable();
                $table->text('client_witness_assessment_team_remark')->nullable();

                $table->unsignedBigInteger('quality_check_id')->nullable();
                $table->text('quality_check_team_ids')->nullable();
                $table->unsignedTinyInteger('quality_status')->nullable();
                $table->text('quality_check_remark')->nullable();

                $table->unsignedBigInteger('accreditation_id')->nullable();
                $table->text('accreditation_team_ids')->nullable();
                $table->unsignedTinyInteger('accreditation_status')->nullable();
                $table->text('accreditation_remark')->nullable();

                $table->date('allot_date')->nullable();
                $table->string('mode_of_auditor', 191)->nullable();
                $table->text('remark')->nullable();

                $table->unsignedTinyInteger('is_surveillance')->default(0);

                $table->timestamps();
            });
        }

        if (!Schema::hasTable('application_payment_details')) {
            Schema::create('application_payment_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('application_id')->nullable()->index();
                $table->unsignedBigInteger('fee_structure_id')->nullable();

                $table->decimal('application_fee', 24, 2)->nullable();
                $table->string('application_fee_image', 255)->nullable();
                $table->unsignedTinyInteger('application_fee_status')->nullable();
                $table->dateTime('application_fee_date')->nullable();

                $table->decimal('document_fee', 24, 2)->nullable();
                $table->string('document_fee_image', 255)->nullable();
                $table->unsignedTinyInteger('document_fee_status')->nullable();
                $table->dateTime('document_fee_date')->nullable();

                $table->decimal('assessment_fee', 24, 2)->nullable();
                $table->string('assessment_fee_image', 255)->nullable();
                $table->unsignedTinyInteger('assessment_fee_status')->nullable();
                $table->dateTime('assessment_fee_date')->nullable();

                $table->decimal('technical_assessment_fee', 24, 2)->nullable();
                $table->string('technical_assessment_fee_image', 255)->nullable();
                $table->unsignedTinyInteger('technical_assessment_fee_status')->nullable();
                $table->dateTime('technical_assessment_date')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('application_payment_details');
        Schema::dropIfExists('applications');
    }
}
