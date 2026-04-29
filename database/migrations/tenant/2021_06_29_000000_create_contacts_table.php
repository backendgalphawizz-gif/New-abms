<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Storefront “contact us” messages from customers (name, email, subject, message, admin reply).
 * Matches installation/backup/database_v13.1.sql — required before 2021_06_30_212619_add_col_to_contact.
 */
class CreateContactsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('contacts')) {
            return;
        }

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_number');
            $table->string('subject');
            $table->text('message');
            $table->boolean('seen')->default(false);
            $table->string('feedback')->default('0');
            $table->longText('reply')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
