<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['title', 'description', 'image', 'teacher_id'];

    public function teacher()
    {
        return $this->belongsTo(User::class , 'teacher_id');
    }
    public function sections()
    {
        return $this->hasMany(Section::class);
    }
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
    public function courseMaterials()
    {
        return $this->hasMany(CourseMaterial::class);
    }

    public function studyGroups()
    {
        return $this->hasMany(StudyGroup::class);
    }

    // Get all students enrolled in any section of this subject
    public function students()
    {
        return User::where('role', 'student')
            ->whereHas('sections', fn($q) => $q->where('subject_id', $this->id));
    }
}
