<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable(); 
            $table->enum('email_frequency', ['daily', 'weekly', 'monthly', 'never'])->default('weekly');
            $table->json('preferred_job_topics')->nullable();
            $table->json('preferred_regions')->nullable();
            $table->json('preferred_job_types')->nullable(); 
            $table->json('preferred_experience_levels')->nullable();
            $table->integer('min_salary')->nullable();
            $table->integer('max_salary')->nullable();
            $table->boolean('remote_only')->default(false);
            $table->boolean('email_notifications_enabled')->default(true);
            $table->boolean('job_alerts_enabled')->default(true);
            $table->boolean('show_recommendations')->default(true);
            $table->timestamp('last_recommendation_update')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'session_id']);
            $table->index('session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};