<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('query_name');
            $table->integer('page')->default(2);
            $table->integer('num_page')->default(20);
            $table->string('timeframe')->default('week');
            $table->string('category_image');
            $table->timestamps();
        });

        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropColumn('job_category');
        });

        Schema::table('job_listings', function (Blueprint $table) {
            $table->foreignId('job_category')->constrained('job_categories');
        });

        $this->migrateExistingCategories();
    }

    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropForeign(['job_category']);
            $table->string('job_category');
        });

        Schema::dropIfExists('job_categories');
    }

    private function migrateExistingCategories(): void
    {
        $categories = config('geezap');
        $categoryModel = new \App\Models\JobCategory();

        foreach ($categories as $key => $config) {
            $categoryModel->create([
                'name' => $key,
                'query_name' => $config['query'],
                'page' => $config['page'],
                'num_page' => $config['num_pages'],
                'timeframe' => $config['date_posted'],
                'category_image' => $config['category_image'],
            ]);
        }
    }
};
