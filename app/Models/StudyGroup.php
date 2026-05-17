<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject_id',
        'teacher_id',
        'max_students',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'study_group_student', 'study_group_id', 'student_id')
                    ->withTimestamps();
    }

    public function submissions()
    {
        return $this->hasMany(MaterialSubmission::class, 'study_group_id');
    }
}
