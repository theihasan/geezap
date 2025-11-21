<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('facebook_token')->nullable()->change();
            $table->text('google_token')->nullable()->change();
            $table->text('github_token')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('facebook_token')->nullable()->change();
            $table->string('google_token')->nullable()->change();
            $table->string('github_token')->nullable()->change();
        });
    }
};
