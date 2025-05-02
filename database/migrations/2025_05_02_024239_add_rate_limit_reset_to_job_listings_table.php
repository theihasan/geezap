<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            $table->dateTime('rate_limit_reset')->nullable()->after('request_remaining');
        });
    }

    public function down(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            $table->dropColumn('rate_limit_reset');
        });
    }
};
