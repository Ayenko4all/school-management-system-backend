<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Requests\CreateSubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use App\Rules\CreateClassroomRule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;

class AdminSubjectController extends RespondsWithHttpStatusController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $Subjects = QueryBuilder::for(Subject::class)
            ->withTrashed()
            ->defaultSort('-created_at')
            ->allowedFilters(['name'])
            ->jsonPaginate()
            ->appends($request->query());

        return $this->respond([
            'Subjects' =>  SubjectResource::collection($Subjects->response()->getData(true))
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSubjectRequest $request)
    {
        $term = Term::where('name', $request->input('term'))->pluck('id')->firstOrFail();

        $Subject = Subject::create([
            'name'  =>  strtolower($request->input('name')),
            'classroom_id'  =>  $request->input('classroom'),
            'term_id'  =>  $term,
            'session_id'  =>  $request->input('session'),
        ]);

        return $this->responseCreated([
            'Subject' => new SubjectResource($Subject->load('classroom'))
        ], 'A Subject was created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $Subject = Subject::query()
                    ->where('id', $id)
                    ->firstOrFail();
        return $this->respond([
            'Subject' =>  new SubjectResource($Subject->load('classroom'))
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
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $request->validate(
            [
                'name'  => ['required', 'string',Rule::unique('subjects')->ignore($id)],
                'status'  => ['required', 'string']
            ],
            [
                '*.required' => 'The :attribute field is required',
            ]
        );

        $Subject =  Subject::query()
                    ->where('id', $id)
                    ->firstOrFail();

        $Subject->update([
            'name'      =>  $request->input('name'),
            'status'    =>  $request->input('status'),
        ]);

        return $this->respond([
            'message' => 'A Subject was updated successfully',
            'Subject' => new SubjectResource($Subject->load('classroom'))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $Subject = Subject::query()
            ->where('id', $id)
            ->withTrashed()
            ->firstOrFail();

        $Subject->update(['status'    => 'inactive']);

        $Subject->delete();

        return $this->responseOk(['message' => 'A Subject was deleted successfully']);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {
        $subject = Subject::query()
            ->where('id', $id)
            ->withTrashed()
            ->firstOrFail();

        $subject->update(['status'    => 'active']);

        $subject->restore();

        return $this->respond([
            'message' => 'A Subject was restore successfully',
            'Subject' => new SubjectResource($subject)
        ]);
    }
}
