<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'section_id',
        'teacher_id',
        'attendance_date',
        'checkin_code',
        'checkin_starts_at',
        'checkin_ends_at',
        'checkin_open',
    ];

    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
            'checkin_starts_at' => 'datetime',
            'checkin_ends_at' => 'datetime',
            'checkin_open' => 'boolean',
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

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function records()
    {
        return $this->hasMany(AttendanceRecord::class, 'attendance_session_id');
    }
}

