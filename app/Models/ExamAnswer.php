<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAnswer extends Model
{
    protected $fillable = ['exam_question_id', 'student_id', 'selected_option_id', 'answer_text', 'is_correct', 'points_earned'];

    protected function casts(): array
    {
        return ['is_correct' => 'boolean'];
    }

    public function question()
    {
        return $this->belongsTo(ExamQuestion::class , 'exam_question_id');
    }
    public function student()
    {
        return $this->belongsTo(User::class , 'student_id');
    }
    public function selectedOption()
    {
        return $this->belongsTo(ExamOption::class , 'selected_option_id');
    }
}
