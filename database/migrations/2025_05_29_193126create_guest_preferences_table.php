<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_preferences', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->string('email')->nullable();
            $table->json('preferred_job_categories_id')->nullable();
            $table->json('preferred_regions_id')->nullable();
            $table->json('preferred_job_types')->nullable();
            $table->boolean('remote_only')->default(false);
            $table->boolean('email_alerts_enabled')->default(false);
            $table->integer('daily_views')->default(0);
            $table->date('last_view_date')->nullable();
            $table->timestamps();
            
            $table->index('session_id');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_preferences');
    }
};