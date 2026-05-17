<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialSubmission extends Model
{
    protected $fillable = ['course_material_id', 'student_id', 'study_group_id', 'file_path', 'teacher_comment', 'commented_at'];

    protected $casts = [
        'commented_at' => 'datetime',
    ];

    public function courseMaterial()
    {
        return $this->belongsTo(CourseMaterial::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class);
    }
}
