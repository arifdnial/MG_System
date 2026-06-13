<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamOption;
use App\Models\Announcement;
use App\Models\CourseMaterial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin ──────────────────────────────────
        $admin = User::create([
            'name' => 'Admin User',
            'ic' => '000000000001',
            'email' => 'admin@edupro.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // ─── Teachers ───────────────────────────────
        $teacher1 = User::create([
            'name' => 'Ustaz Ahmad',
            'ic' => '880101012345',
            'email' => 'ahmad@edupro.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '012-3456789',
        ]);

        $teacher2 = User::create([
            'name' => 'Ustazah Fatimah',
            'ic' => '850615065432',
            'email' => 'fatimah@edupro.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '013-9876543',
        ]);

        // ─── Students ───────────────────────────────
        $students = [];
        $studentData = [
            ['name' => 'Ali bin Abu', 'ic' => '100101011234', 'email' => 'ali@student.com', 'form_class' => 'Form 3A'],
            ['name' => 'Siti Aminah', 'ic' => '100202021234', 'email' => 'siti@student.com', 'form_class' => 'Form 3A'],
            ['name' => 'Muhammad Imran', 'ic' => '100303031234', 'email' => 'imran@student.com', 'form_class' => 'Form 3B'],
            ['name' => 'Nurul Aisyah', 'ic' => '100404041234', 'email' => 'aisyah@student.com', 'form_class' => 'Form 3B'],
            ['name' => 'Hafiz bin Hamzah', 'ic' => '100505051234', 'email' => 'hafiz@student.com', 'form_class' => 'Form 4A'],
        ];

        foreach ($studentData as $s) {
            $students[] = User::create(array_merge($s, [
                'password' => Hash::make('password'),
                'role' => 'student',
            ]));
        }

        // ─── Subjects ───────────────────────────────
        $mathSubject = Subject::create([
            'title' => 'Mathematics',
            'description' => 'Form 3 Mathematics covering algebra, geometry, and statistics.',
            'teacher_id' => $teacher1->id,
        ]);

        $scienceSubject = Subject::create([
            'title' => 'Science',
            'description' => 'Form 3 Science covering biology, chemistry, and physics fundamentals.',
            'teacher_id' => $teacher1->id,
        ]);

        $englishSubject = Subject::create([
            'title' => 'English Language',
            'description' => 'Form 3 English Language - grammar, writing, and comprehension.',
            'teacher_id' => $teacher2->id,
        ]);

        // ─── Sections ───────────────────────────────
        $math3A = Section::create(['name' => 'Form 3A', 'subject_id' => $mathSubject->id]);
        $math3B = Section::create(['name' => 'Form 3B', 'subject_id' => $mathSubject->id]);
        $sci3A = Section::create(['name' => 'Form 3A', 'subject_id' => $scienceSubject->id]);
        $eng3A = Section::create(['name' => 'Form 3A', 'subject_id' => $englishSubject->id]);

        // Enroll students
        $math3A->students()->attach([$students[0]->id, $students[1]->id]);
        $math3B->students()->attach([$students[2]->id, $students[3]->id]);
        $sci3A->students()->attach([$students[0]->id, $students[1]->id, $students[2]->id]);
        $eng3A->students()->attach([$students[0]->id, $students[1]->id]);

        // ─── Exams ──────────────────────────────────
        $exam1 = Exam::create([
            'title' => 'Chapter 1-3 Test',
            'subject_id' => $mathSubject->id,
            'section_id' => $math3A->id,
            'description' => 'Mid-term test covering chapters 1, 2, and 3.',
            'exam_date' => now()->addDays(7),
            'created_by' => $teacher1->id,
        ]);

        // MCQ Question 1
        $q1 = ExamQuestion::create([
            'exam_id' => $exam1->id,
            'type' => 'mcq',
            'question_text' => 'What is 2 + 2?',
            'points' => 2,
            'order' => 1,
        ]);
        ExamOption::create(['exam_question_id' => $q1->id, 'option_text' => '3', 'is_correct' => false]);
        ExamOption::create(['exam_question_id' => $q1->id, 'option_text' => '4', 'is_correct' => true]);
        ExamOption::create(['exam_question_id' => $q1->id, 'option_text' => '5', 'is_correct' => false]);
        ExamOption::create(['exam_question_id' => $q1->id, 'option_text' => '6', 'is_correct' => false]);

        // MCQ Question 2
        $q2 = ExamQuestion::create([
            'exam_id' => $exam1->id,
            'type' => 'mcq',
            'question_text' => 'Solve for x: 3x = 12',
            'points' => 3,
            'order' => 2,
        ]);
        ExamOption::create(['exam_question_id' => $q2->id, 'option_text' => '2', 'is_correct' => false]);
        ExamOption::create(['exam_question_id' => $q2->id, 'option_text' => '3', 'is_correct' => false]);
        ExamOption::create(['exam_question_id' => $q2->id, 'option_text' => '4', 'is_correct' => true]);
        ExamOption::create(['exam_question_id' => $q2->id, 'option_text' => '6', 'is_correct' => false]);

        // Subjective Question
        ExamQuestion::create([
            'exam_id' => $exam1->id,
            'type' => 'subjective',
            'question_text' => 'Explain the Pythagorean theorem and give one real-world example.',
            'points' => 5,
            'order' => 3,
        ]);

        // ─── Announcements ──────────────────────────
        Announcement::create([
            'title' => 'Welcome to Mathematics!',
            'content' => 'Welcome to the new semester. Please review the syllabus and come prepared for class. Our first topic will be Algebraic Expressions.',
            'subject_id' => $mathSubject->id,
            'teacher_id' => $teacher1->id,
        ]);

        Announcement::create([
            'title' => 'Science Lab Safety Guidelines',
            'content' => 'Please read the lab safety rules before our first practical session next week. Safety goggles are mandatory.',
            'subject_id' => $scienceSubject->id,
            'teacher_id' => $teacher1->id,
        ]);

        Announcement::create([
            'title' => 'English Essay Submission',
            'content' => 'Please submit your essay on "My Favourite Holiday" by Friday. Minimum 300 words.',
            'subject_id' => $englishSubject->id,
            'teacher_id' => $teacher2->id,
        ]);

        // ─── Course Materials ───────────────────────
        CourseMaterial::create([
            'title' => 'Introduction to Algebra (Video)',
            'description' => 'Watch this introductory video on algebraic expressions.',
            'subject_id' => $mathSubject->id,
            'teacher_id' => $teacher1->id,
            'video_url' => 'https://www.youtube.com/watch?v=example',
        ]);

        CourseMaterial::create([
            'title' => 'Chapter 1 Notes',
            'description' => 'Comprehensive notes for Chapter 1 - Numbers and Operations.',
            'subject_id' => $mathSubject->id,
            'teacher_id' => $teacher1->id,
        ]);

        $this->command->info('');
        $this->command->info('═════════════════════════════════════════════');
        $this->command->info('  MGM-System Database Seeded Successfully!  ');
        $this->command->info('═════════════════════════════════════════════');
        $this->command->info('');
        $this->command->info('  Login Credentials (password: "password"):');
        $this->command->info('  ─────────────────────────────────────────');
        $this->command->info('  Admin:   IC 000000000001');
        $this->command->info('  Teacher: IC 880101012345 (Ustaz Ahmad)');
        $this->command->info('  Teacher: IC 850615065432 (Ustazah Fatimah)');
        $this->command->info('  Student: IC 100101011234 (Ali bin Abu)');
        $this->command->info('  Student: IC 100202021234 (Siti Aminah)');
        $this->command->info('');
    }
}
