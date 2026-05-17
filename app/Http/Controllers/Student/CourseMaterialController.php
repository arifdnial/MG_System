<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseMaterial;
use App\Models\MaterialSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseMaterialController extends Controller
{
    public function submit(Request $request, CourseMaterial $material)
    {
        $request->validate([
            'submission_file' => 'required|file|mimes:pdf,png,jpg,jpeg,doc,docx,zip|max:20480',
        ]);

        // Verify student is enrolled in the subject
        $user = auth()->user();
        if (!$user->sections()->where('subject_id', $material->subject_id)->exists()) {
            abort(403, 'You are not enrolled in this subject.');
        }

        // Store file
        $path = $request->file('submission_file')->store('submissions/' . $material->id, 'public');

        $studyGroupId = null;
        if ($material->is_group_activity) {
            $group = $user->studyGroups()->where('subject_id', $material->subject_id)->first();
            if (!$group) {
                return back()->with('error', 'You must join a group first for this activity.');
            }
            $studyGroupId = $group->id;
        }

        // Create or update submission (clear teacher comment on resubmit)
        MaterialSubmission::updateOrCreate(
            $material->is_group_activity 
                ? ['course_material_id' => $material->id, 'study_group_id' => $studyGroupId]
                : ['course_material_id' => $material->id, 'student_id' => $user->id],
            [
                'file_path'       => $path,
                'student_id'      => $user->id, // Still track who was the direct uploader
                'teacher_comment' => null,
                'commented_at'    => null,
            ]
        );

        return back()->with('success', 'Your activity has been submitted successfully!');
    }
}
