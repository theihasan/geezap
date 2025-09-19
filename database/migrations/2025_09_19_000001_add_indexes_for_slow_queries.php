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
            if (!Schema::hasColumn('job_listings', 'views')) {
                $table->unsignedBigInteger('views')->default(0)->after('salary_period');
            }

            $table->index(['job_category', 'id'], 'job_listings_category_id_index');
            $table->index(['country', 'created_at'], 'job_listings_country_created_index');
        });

        Schema::table('job_apply_options', function (Blueprint $table) {
            $table->index(['job_listing_id', 'publisher'], 'job_apply_options_job_publisher_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropIndex('job_listings_category_id_index');
            $table->dropIndex('job_listings_country_created_index');
        });

        Schema::table('job_apply_options', function (Blueprint $table) {
            $table->dropIndex('job_apply_options_job_publisher_idx');
        });
    }
};


