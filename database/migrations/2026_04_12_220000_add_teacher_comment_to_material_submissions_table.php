<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_submissions', function (Blueprint $table) {
            $table->text('teacher_comment')->nullable()->after('file_path');
            $table->timestamp('commented_at')->nullable()->after('teacher_comment');
        });
    }

    public function down(): void
    {
        Schema::table('material_submissions', function (Blueprint $table) {
            $table->dropColumn(['teacher_comment', 'commented_at']);
        });
    }
};
