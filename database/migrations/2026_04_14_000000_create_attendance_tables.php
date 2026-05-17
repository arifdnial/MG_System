<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->date('attendance_date');
            $table->timestamps();

            $table->unique(['subject_id', 'section_id', 'attendance_date']);
        });

        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_session_id')->constrained('attendance_sessions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['present', 'absent'])->default('absent');
            $table->string('note')->nullable();
            $table->timestamps();

            $table->unique(['attendance_session_id', 'student_id']);
            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
        Schema::dropIfExists('attendance_sessions');
    }
};

