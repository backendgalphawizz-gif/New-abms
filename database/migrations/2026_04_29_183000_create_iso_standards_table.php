<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('central')->create('iso_standards', function (Blueprint $table) {
            $table->id();
            $table->string('code', 80)->unique();
            $table->string('name', 255);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $defaults = [
            ['code' => 'ISO 9001:2015', 'name' => 'Quality Management System'],
            ['code' => 'ISO 14001:2015', 'name' => 'Environmental Management'],
            ['code' => 'ISO 45001:2018', 'name' => 'Occupational Health & Safety'],
            ['code' => 'ISO 20000-1:2018', 'name' => 'IT Service Management'],
            ['code' => 'ISO 22000:2018', 'name' => 'Food Safety Management'],
            ['code' => 'ISO 27001:2022', 'name' => 'Information Security'],
            ['code' => 'ISO 50001:2018', 'name' => 'Energy Management System'],
            ['code' => 'ISO 13485:2016', 'name' => 'Medical Devices Quality'],
            ['code' => 'ISO 22301:2019', 'name' => 'Business Continuity Management'],
            ['code' => 'ISO 21001:2018', 'name' => 'Educational Organizations'],
            ['code' => 'ISO 55001:2024', 'name' => 'Asset Management System'],
            ['code' => 'ISO 41001:2018', 'name' => 'Facility Management System'],
            ['code' => 'ISO 37001:2016', 'name' => 'Anti-Bribery Management'],
            ['code' => 'ISO 42001:2023', 'name' => 'Artificial Intelligence Management'],
            ['code' => 'ISO/IEC 27701:2019', 'name' => 'Privacy Information Management'],
        ];

        $now = now();
        foreach ($defaults as $index => $row) {
            DB::connection('central')->table('iso_standards')->insert([
                'code' => $row['code'],
                'name' => $row['name'],
                'sort_order' => $index + 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('central')->dropIfExists('iso_standards');
    }
};
