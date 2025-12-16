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
        Schema::create('job_recommendations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_job_id');
            $table->unsignedBigInteger('recommended_job_id');
            $table->decimal('relevance_score', 5, 2)->default(0.00);
            $table->timestamp('computed_at');
            $table->timestamps();

            $table->foreign('source_job_id')->references('id')->on('job_listings')->onDelete('cascade');
            $table->foreign('recommended_job_id')->references('id')->on('job_listings')->onDelete('cascade');
            
            $table->index(['source_job_id', 'relevance_score']);
            $table->unique(['source_job_id', 'recommended_job_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_recommendations');
    }
};
