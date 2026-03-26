<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['subject_id', 'section_id', 'title', 'description', 'exam_date', 'marks_released', 'created_by'];

    protected function casts(): array
    {
        return [
            'exam_date' => 'datetime',
            'marks_released' => 'boolean',
        ];
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class , 'created_by');
    }
    public function questions()
    {
        return $this->hasMany(ExamQuestion::class)->orderBy('order');
    }

    public function totalPoints()
    {
        return $this->questions()->sum('points');
    }
}
