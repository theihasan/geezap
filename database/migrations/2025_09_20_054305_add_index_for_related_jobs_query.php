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
            // Add composite index for efficient related jobs queries
            // This index will make the WHERE job_category = ? AND id != ? ORDER BY RAND() LIMIT 3 query much faster
            $table->index(['job_category', 'id'], 'idx_job_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropIndex('idx_job_category_id');
        });
    }
};