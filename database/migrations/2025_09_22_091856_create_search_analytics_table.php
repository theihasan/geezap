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
        Schema::create('search_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('query', 255)->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->index();
            $table->text('user_agent')->nullable();
            $table->integer('results_count')->default(0);
            $table->json('filters_applied')->nullable(); // Store applied filters
            $table->string('session_id', 255)->index();
            $table->timestamp('searched_at')->useCurrent()->index();
            $table->timestamps();

            // Composite indexes for performance
            $table->index(['query', 'searched_at']);
            $table->index(['user_id', 'searched_at']);
            $table->index(['ip_address', 'searched_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_analytics');
    }
};
