<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Teacher\ExamController as TeacherExamController;
use App\Http\Controllers\Teacher\AnnouncementController;
use App\Http\Controllers\Teacher\CourseMaterialController;
use App\Http\Controllers\Student\StudentController;
use Illuminate\Support\Facades\Route;

// ─── Public / Auth ───────────────────────────────────────────
Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class , 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class , 'login']);
    Route::get('/register', [AuthController::class , 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class , 'register']);
    Route::get('/forgot-password', [AuthController::class , 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class , 'forgotPassword']);
    Route::get('/reset-password/{token}', [AuthController::class , 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class , 'resetPassword'])->name('password.update');
});

// Admin invitation (public – no auth required)
Route::get('/admin/accept-invite/{token}', [AdminController::class , 'showAcceptInvite'])->name('admin.accept-invite');
Route::post('/admin/accept-invite/{token}', [AdminController::class , 'acceptInvite'])->name('admin.accept-invite.store');

Route::post('/logout', [AuthController::class , 'logout'])->middleware('auth')->name('logout');
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
});

// ─── Admin Routes ────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class , 'dashboard'])->name('dashboard');

    // User management (teachers, students, admins)
    Route::get('/users/{role}', [AdminController::class , 'usersIndex'])->name('users.index');
    Route::get('/users/{role}/create', [AdminController::class , 'usersCreate'])->name('users.create');
    Route::post('/users/{role}', [AdminController::class , 'usersStore'])->name('users.store');
    Route::get('/users/{role}/{user}/edit', [AdminController::class , 'usersEdit'])->name('users.edit');
    Route::put('/users/{role}/{user}', [AdminController::class , 'usersUpdate'])->name('users.update');
    Route::delete('/users/{role}/{user}', [AdminController::class , 'usersDestroy'])->name('users.destroy');

    // Admin invitation
    Route::get('/invite', [AdminController::class , 'showInvite'])->name('invite');
    Route::post('/invite', [AdminController::class , 'sendInvite'])->name('invite.send');
});

// ─── Teacher Routes ──────────────────────────────────────────
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class , 'dashboard'])->name('dashboard');
    Route::get('/profile', [TeacherController::class , 'profile'])->name('profile');
    Route::put('/profile', [TeacherController::class , 'updateProfile'])->name('profile.update');

    // Subjects
    Route::get('/subjects', [TeacherController::class , 'subjectsIndex'])->name('subjects.index');
    Route::get('/subjects/create', [TeacherController::class , 'subjectsCreate'])->name('subjects.create');
    Route::post('/subjects', [TeacherController::class , 'subjectsStore'])->name('subjects.store');
    Route::get('/subjects/{subject}', [TeacherController::class , 'subjectsShow'])->name('subjects.show');
    Route::put('/subjects/{subject}', [TeacherController::class , 'subjectsUpdate'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [TeacherController::class , 'subjectsDestroy'])->name('subjects.destroy');

    // Sections
    Route::post('/subjects/{subject}/sections', [TeacherController::class , 'sectionsStore'])->name('sections.store');
    Route::delete('/subjects/{subject}/sections/{section}', [TeacherController::class , 'sectionsDestroy'])->name('sections.destroy');
    Route::post('/subjects/{subject}/sections/{section}/students', [TeacherController::class , 'sectionsAddStudent'])->name('sections.addStudent');
    Route::delete('/subjects/{subject}/sections/{section}/students/{student}', [TeacherController::class , 'sectionsRemoveStudent'])->name('sections.removeStudent');

    // Exams
    Route::get('/exams', [TeacherExamController::class , 'index'])->name('exams.index');
    Route::get('/exams/create', [TeacherExamController::class , 'create'])->name('exams.create');
    Route::post('/exams', [TeacherExamController::class , 'store'])->name('exams.store');
    Route::get('/exams/{exam}', [TeacherExamController::class , 'show'])->name('exams.show');
    Route::post('/exams/{exam}/questions', [TeacherExamController::class , 'addQuestion'])->name('exams.addQuestion');
    Route::delete('/exams/{exam}/questions/{question}', [TeacherExamController::class , 'deleteQuestion'])->name('exams.deleteQuestion');
    Route::post('/exams/{exam}/toggle-marks', [TeacherExamController::class , 'toggleMarks'])->name('exams.toggleMarks');
    Route::delete('/exams/{exam}', [TeacherExamController::class , 'destroy'])->name('exams.destroy');

    // Announcements
    Route::get('/announcements', [AnnouncementController::class , 'index'])->name('announcements.index');
    Route::get('/announcements/create', [AnnouncementController::class , 'create'])->name('announcements.create');
    Route::post('/announcements', [AnnouncementController::class , 'store'])->name('announcements.store');
    Route::get('/announcements/{announcement}/edit', [AnnouncementController::class , 'edit'])->name('announcements.edit');
    Route::put('/announcements/{announcement}', [AnnouncementController::class , 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class , 'destroy'])->name('announcements.destroy');

    // Course Materials
    Route::get('/materials', [CourseMaterialController::class , 'index'])->name('materials.index');
    Route::get('/materials/create', [CourseMaterialController::class , 'create'])->name('materials.create');
    Route::post('/materials', [CourseMaterialController::class , 'store'])->name('materials.store');
    Route::delete('/materials/{material}', [CourseMaterialController::class , 'destroy'])->name('materials.destroy');
});

// ─── Student Routes ──────────────────────────────────────────
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class , 'dashboard'])->name('dashboard');
    Route::get('/profile', [StudentController::class , 'profile'])->name('profile');
    Route::put('/profile', [StudentController::class , 'updateProfile'])->name('profile.update');

    // Subjects
    Route::get('/subjects', [StudentController::class , 'subjectsIndex'])->name('subjects.index');
    Route::get('/subjects/{subject}', [StudentController::class , 'subjectsShow'])->name('subjects.show');

    // Exams
    Route::get('/exams', [StudentController::class , 'examsIndex'])->name('exams.index');
    Route::get('/exams/{exam}/take', [StudentController::class , 'examsTake'])->name('exams.take');
    Route::post('/exams/{exam}/submit', [StudentController::class , 'examsSubmit'])->name('exams.submit');
    Route::get('/exams/{exam}/result', [StudentController::class , 'examsResult'])->name('exams.result');

    // Marks
    Route::get('/marks', [StudentController::class , 'marks'])->name('marks');
});
