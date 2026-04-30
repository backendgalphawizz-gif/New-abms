<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToAssessorsTable extends Migration
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
            if (!Schema::hasColumn('assessors', 'residence_tel')) {
                $table->string('residence_tel', 191)->nullable();
            }
            if (!Schema::hasColumn('assessors', 'training')) {
                $table->text('training')->nullable();
            }
            if (!Schema::hasColumn('assessors', 'specific_knowledge_gained')) {
                $table->text('specific_knowledge_gained')->nullable();
            }
            if (!Schema::hasColumn('assessors', 'additional_information')) {
                $table->text('additional_information')->nullable();
            }
            if (!Schema::hasColumn('assessors', 'professional_experience')) {
                $table->json('professional_experience')->nullable();
            }
            if (!Schema::hasColumn('assessors', 'assessment_summery')) {
                $table->json('assessment_summery')->nullable();
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
            $cols = [
                'residence_tel',
                'training',
                'specific_knowledge_gained',
                'additional_information',
                'professional_experience',
                'assessment_summery',
            ];
            $drop = [];
            foreach ($cols as $col) {
                if (Schema::hasColumn('assessors', $col)) {
                    $drop[] = $col;
                }
            }
            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
}
