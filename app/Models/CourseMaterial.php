<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    protected $fillable = ['subject_id', 'teacher_id', 'title', 'description', 'video_url', 'file_path', 'document_path', 'is_group_activity'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function submissions()
    {
        return $this->hasMany(MaterialSubmission::class);
    }
}
