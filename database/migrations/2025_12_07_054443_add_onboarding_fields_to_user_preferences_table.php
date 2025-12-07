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
        Schema::table('user_preferences', function (Blueprint $table) {
            $table->boolean('email_notifications')->default(true)->after('email_notifications_enabled');
            $table->boolean('job_alerts')->default(false)->after('email_notifications');
            $table->boolean('newsletter')->default(true)->after('job_alerts');
            $table->boolean('marketing_emails')->default(false)->after('newsletter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_preferences', function (Blueprint $table) {
            $table->dropColumn(['email_notifications', 'job_alerts', 'newsletter', 'marketing_emails']);
        });
    }
};
