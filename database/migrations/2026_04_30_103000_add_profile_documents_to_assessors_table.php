<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileDocumentsToAssessorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('assessors')) {
            return;
        }

        Schema::table('assessors', function (Blueprint $table) {
            if (!Schema::hasColumn('assessors', 'qualification_document')) {
                $table->string('qualification_document', 255)->nullable();
            }
            if (!Schema::hasColumn('assessors', 'work_experience_document')) {
                $table->string('work_experience_document', 255)->nullable();
            }
            if (!Schema::hasColumn('assessors', 'consultancy_document')) {
                $table->string('consultancy_document', 255)->nullable();
            }
            if (!Schema::hasColumn('assessors', 'audit_document')) {
                $table->string('audit_document', 255)->nullable();
            }
            if (!Schema::hasColumn('assessors', 'training_document')) {
                $table->string('training_document', 255)->nullable();
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
            $dropColumns = [];
            foreach (['qualification_document', 'work_experience_document', 'consultancy_document', 'audit_document', 'training_document'] as $column) {
                if (Schema::hasColumn('assessors', $column)) {
                    $dropColumns[] = $column;
                }
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
}
