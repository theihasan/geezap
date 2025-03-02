<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->text('employer_logo')->nullable()->change();
            $table->text('google_link')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->string('employer_logo')->nullable()->change();
            $table->string('google_link')->nullable()->change();
        });
    }
};
