<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        // Subjects (replaces courses) - owned by a teacher
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Sections (class sections within a subject, e.g. Form 1A, Form 2B)
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Form 1A"
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Pivot: students enrolled in sections
        Schema::create('section_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['section_id', 'student_id']);
        });

        // Exams
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('exam_date')->nullable();
            $table->boolean('marks_released')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Exam Questions
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['mcq', 'subjective']);
            $table->text('question_text');
            $table->integer('points')->default(1);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Exam Options (for MCQ)
        Schema::create('exam_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_question_id')->constrained()->onDelete('cascade');
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });

        // Exam Answers (student submissions)
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_question_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('selected_option_id')->nullable()->constrained('exam_options')->onDelete('set null');
            $table->text('answer_text')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->integer('points_earned')->default(0);
            $table->timestamps();
            $table->unique(['exam_question_id', 'student_id']);
        });

        // Announcements
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->string('video_link')->nullable();
            $table->timestamps();
        });

        // Course Materials
        Schema::create('course_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_url')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        // Notifications (user-scoped)
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('info');
            $table->boolean('is_read')->default(false);
            $table->string('link')->nullable();
            $table->timestamps();
        });

        // Admin Invitations
        Schema::create('admin_invitations', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('ic');
            $table->string('token')->unique();
            $table->boolean('used')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_invitations');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('course_materials');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('exam_answers');
        Schema::dropIfExists('exam_options');
        Schema::dropIfExists('exam_questions');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('section_student');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('subjects');
    }
};
