<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'ic', 'email', 'phone', 'role', 'form_class', 'password', 'is_active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    // Teacher relationships
    public function subjects()
    {
        return $this->hasMany(Subject::class , 'teacher_id');
    }
    public function announcements()
    {
        return $this->hasMany(Announcement::class , 'teacher_id');
    }
    public function courseMaterials()
    {
        return $this->hasMany(CourseMaterial::class , 'teacher_id');
    }

    // Student relationships
    public function sections()
    {
        return $this->belongsToMany(Section::class , 'section_student', 'student_id');
    }
    public function examAnswers()
    {
        return $this->hasMany(ExamAnswer::class , 'student_id');
    }

    // Shared
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function dashboardRoute(): string
    {
        return match ($this->role) {
                'admin' => 'admin.dashboard',
                'teacher' => 'teacher.dashboard',
                'student' => 'student.dashboard',
                default => 'login',
            };
    }
}
