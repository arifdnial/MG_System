<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        $courses = Course::with('students')->get();
        return view('enrollments.index', compact('courses'));
    }

    public function create()
    {
        $students = Student::all();
        $courses = Course::all();
        return view('enrollments.create', compact('students', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $student = Student::find($request->student_id);
        
        if ($student->courses()->where('course_id', $request->course_id)->exists()) {
            return redirect()->back()->with('error', 'Student is already enrolled in this course.');
        }

        $student->courses()->attach($request->course_id);

        return redirect()->route('enrollments.index')->with('success', 'Student enrolled successfully.');
    }

    public function show(Course $course)
    {
        $course->load('students');
        return view('enrollments.show', compact('course'));
    }

    public function destroy(Request $request)
    {
        $student = Student::find($request->student_id);
        $student->courses()->detach($request->course_id);

        return redirect()->route('enrollments.index')->with('success', 'Student unenrolled successfully.');
    }
}
