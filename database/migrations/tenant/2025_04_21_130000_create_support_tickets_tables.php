<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Support ticket feature (admin sidebar badge, customer account tickets, APIs).
 * Missing on trimmed tenant DBs; creates minimal schema used by SupportTicket models.
 */
class CreateSupportTicketsTables extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('support_tickets')) {
            Schema::create('support_tickets', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('subject', 255)->nullable();
                $table->string('type', 100)->nullable();
                $table->unsignedBigInteger('customer_id')->nullable()->index();
                $table->string('priority', 50)->nullable();
                $table->text('description')->nullable();
                $table->string('status', 32)->default('open')->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('support_ticket_convs')) {
            Schema::create('support_ticket_convs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('support_ticket_id')->nullable()->index();
                $table->unsignedBigInteger('admin_id')->nullable();
                $table->text('customer_message')->nullable();
                $table->text('admin_message')->nullable();
                $table->unsignedTinyInteger('position')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('support_ticket_convs');
        Schema::dropIfExists('support_tickets');
    }
}
