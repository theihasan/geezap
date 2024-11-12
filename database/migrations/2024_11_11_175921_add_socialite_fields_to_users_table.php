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
            $table->after('password', function (Blueprint $table) {
                $table->string('facebook_id')->nullable();
                $table->string('facebook_token')->nullable();
                $table->string('google_id')->nullable();
                $table->string('google_token')->nullable();
                $table->string('github_id')->nullable();
                $table->string('github_token')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('facebook_id');
            $table->dropColumn('facebook_token');
            $table->dropColumn('google_id');
            $table->dropColumn('google_token');
            $table->dropColumn('github_id');
            $table->dropColumn('github_token');
        });
    }
};
