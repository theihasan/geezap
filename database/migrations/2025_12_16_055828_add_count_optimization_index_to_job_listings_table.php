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
        Schema::table('job_listings', function (Blueprint $table) {
            $table->index(['id'], 'job_listings_count');
            $table->index(['created_at'], 'job_listings_created_at');
            $table->index(['job_category'], 'job_listings_job_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropIndex('job_listings_count');
            $table->dropIndex('job_listings_created_at');
            $table->dropIndex('job_listings_job_category');
        });
    }
};
