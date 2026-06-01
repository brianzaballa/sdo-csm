<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->timestamp('started_at')->nullable()->after('is_flagged');
            $table->timestamp('ended_at')->nullable()->after('started_at');
            $table->unsignedInteger('duration_seconds')->nullable()->after('ended_at');
        });
    }

    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropColumn(['started_at', 'ended_at', 'duration_seconds']);
        });
    }
};
