<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            // Add indexes to columns frequently used in filtering
            $table->index('publisher');
            $table->index('country');
            $table->index('job_category');
            $table->index('is_remote');
            $table->index('employment_type');
            $table->index('created_at'); // For sorting by latest
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['publisher']);
            $table->dropIndex(['country']);
            $table->dropIndex(['job_category']);
            $table->dropIndex(['is_remote']);
            $table->dropIndex(['employment_type']);
            $table->dropIndex(['created_at']);
        });
    }
};
