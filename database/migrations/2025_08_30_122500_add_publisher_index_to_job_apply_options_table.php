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
        Schema::table('job_apply_options', function (Blueprint $table) {
            // Add composite index for job_listing_id and publisher for better performance
            $table->index(['job_listing_id', 'publisher'], 'job_apply_options_job_publisher_index');
            
            // Add individual index on publisher for faster lookups
            $table->index('publisher', 'job_apply_options_publisher_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_apply_options', function (Blueprint $table) {
            $table->dropIndex('job_apply_options_job_publisher_index');
            $table->dropIndex('job_apply_options_publisher_index');
        });
    }
};
