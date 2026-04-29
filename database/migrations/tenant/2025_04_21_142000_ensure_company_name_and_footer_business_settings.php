<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Admin footer and layouts expect business_settings rows (e.g. company_name).
 * The initial tenant create migration did not seed all of them; add missing keys.
 */
class EnsureCompanyNameAndFooterBusinessSettings extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('business_settings')) {
            return;
        }

        $now = now();
        $defaults = [
            'company_name' => ['value' => config('app.name', 'CAB Management System')],
            'company_copyright_text' => ['value' => ''],
            'seller_pos' => ['value' => '0'],
        ];

        foreach ($defaults as $type => $row) {
            $exists = DB::table('business_settings')->where('type', $type)->exists();
            if (!$exists) {
                DB::table('business_settings')->insert([
                    'type' => $type,
                    'value' => $row['value'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        // Intentionally no-op: do not delete rows that may have been edited in production.
    }
}
