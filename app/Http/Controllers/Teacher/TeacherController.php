<?php

namespace App\Http\Controllers\Teacher;

use App\Actions\CreateFileUpdateAction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Requests\CreateTeacherRequest;
use App\Http\Resources\ClassroomResource;
use App\Http\Resources\StudentResource;
use App\Http\Resources\SubjectResource;
use App\Http\Resources\TeacherResource;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use CreateTeachersTable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TeacherController extends RespondsWithHttpStatusController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $teacher = Teacher::query()
        ->where('user_id', auth()->id())
        ->firstOrFail();

        $classrooms = $teacher->whereRelation('classrooms', 'teacher_id', '=', $teacher->id)->get();

        $students = $teacher->whereRelation('students', 'teacher_id', '=', $teacher->id)->get();

        $subjects = $teacher->whereRelation('subjects', 'teacher_id', '=', $teacher->id)->get();

        $this->authorize('viewTeacher', $teacher);

        return $this->respond([
            'students' => StudentResource::collection($students),
            'subjects' => SubjectResource::collection($subjects),
            'classrooms' => ClassroomResource::collection($classrooms),
            'studentCount' => $students->count(),
            'subjectCount' => $subjects->count(),
            'classroomCount' => $classrooms->count()
        ]);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTeacherRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateTeacherRequest $request, CreateFileUpdateAction $createFileUpdateAction)
    {
        if (Teacher::where('user_id', auth()->id())->exists()) {
            throw ValidationException::withMessages(['message' => 'Student already exists.']);
        }

        $teacher = Teacher::create([
            'user_id'                      => auth()->id(),
            'martial_status'                => $request->input('martial_status'),
            'educational_level'             => $request->input('educational_level'),
            'tertiary_institution'          => $request->input('tertiary_institution'),
            'graduating_date'               => $request->input('graduating_date'),
            'primary_school'                => $request->input('primary_school'),
            'secondary_school'              => $request->input('secondary_school'),
            'others_institution'            => $request->input('others_institution'),
            'guarantor_one'                 => $request->input('guarantor_one'),
            'guarantor_two'                 => $request->input('guarantor_two'),
            'identity_card'                 => $request->input('identity_card'),
        ]);

        $createFileUpdateAction->execute($request, $teacher);

        return $this->responseCreated([
            'Teacher' => new TeacherResource($teacher)
        ],  'Form submitted successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $teacher = Teacher::query()
            ->where('id', '=', $id)
            ->where('user_id', '=', auth()->id())
            ->firstOrFail();

        $this->authorize('viewTeacher', $teacher);

        return $this->respond([
            'teacher' => new TeacherResource($teacher->load(['user','classrooms', 'subjects', 'students'])),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
