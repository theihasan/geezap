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
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('employer_name');
            $table->string('employer_logo')->nullable();
            $table->string('employer_website')->nullable();
            $table->string('employer_company_type')->nullable();
            $table->string('publisher')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('job_title');
            $table->string('slug');
            $table->string('job_category');
            $table->longText('apply_link');
            $table->text('description')->nullable();
            $table->boolean('is_remote')->default(false);
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('google_link')->nullable();
            $table->string('posted_at');
            $table->string('expaire_at')->nullable();
            $table->decimal('min_salary', 10, 2)->nullable();
            $table->decimal('max_salary', 10, 2)->nullable();
            $table->string('salary_currency')->nullable();
            $table->string('salary_period')->nullable();
            $table->json('benefits')->nullable();
            $table->json('qualifications')->nullable();
            $table->json('responsibilities')->nullable();
            $table->string('required_experience')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
