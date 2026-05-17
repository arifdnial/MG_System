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
        Schema::table('material_submissions', function (Blueprint $table) {
            $table->foreignId('study_group_id')->nullable()->after('student_id')->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('material_submissions', function (Blueprint $table) {
            $table->dropForeign(['study_group_id']);
            $table->dropColumn('study_group_id');
        });
    }
};
