<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->string('checkin_code', 20)->nullable()->unique()->after('attendance_date');
            $table->dateTime('checkin_starts_at')->nullable()->after('checkin_code');
            $table->dateTime('checkin_ends_at')->nullable()->after('checkin_starts_at');
            $table->boolean('checkin_open')->default(false)->after('checkin_ends_at');
        });

        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dateTime('checked_in_at')->nullable()->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropColumn('checked_in_at');
        });

        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropUnique(['checkin_code']);
            $table->dropColumn(['checkin_code', 'checkin_starts_at', 'checkin_ends_at', 'checkin_open']);
        });
    }
};

