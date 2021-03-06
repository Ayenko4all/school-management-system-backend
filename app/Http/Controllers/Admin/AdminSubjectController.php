<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Requests\CreateSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use App\Models\SubjectType;
use App\Models\Term;
use App\Models\User;
use App\Rules\CreateClassroomRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
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
        $subjects = QueryBuilder::for(Subject::class)
            ->withTrashed()
            ->with(['subject_type','classrooms','term','session'])
            ->defaultSort('-created_at')
            ->allowedSorts(['name','status'])
            ->allowedFilters(['name'])
            ->jsonPaginate()
            ->appends($request->query());

        return $this->respond([
            'Subjects' =>  SubjectResource::collection($subjects)->response()->getData(true)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSubjectRequest $request)
    {
        $term = Term::where('name', $request->input('term'))->pluck('id')->first();

        $subject = Subject::create([
            'name'  =>  strtolower($request->input('name')),
            'term_id'  =>  $term,
            'session_id'  =>  $request->input('session'),
            'subject_type_id'  =>  $request->input('subject_type'),
        ]);

        $subject->classrooms()->attach($request->input('classroom'));

        return $this->responseCreated([
            'Subject' => new SubjectResource($subject->load(['classrooms','term','session']))
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
        $subject = QueryBuilder::for(Subject::where('id', $id))
            ->allowedIncludes(['classrooms','term','session'])
            ->firstOrFail();

        return $this->respond([
            'Subject' =>  new SubjectResource($subject->load(['subject_type','classrooms','term','session']))
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
     * @param UpdateSubjectRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSubjectRequest $request, $id)
    {
        $subject = QueryBuilder::for(Subject::where('id', $id))
            ->allowedIncludes(['classrooms','terms'])
            ->firstOrFail();

        $subject->update([
            'name'  =>  strtolower($request->input('name')),
            'term_id'  =>  $request->input('term'),
            'session_id'  =>  $request->input('session'),
            'subject_type_id'  =>  $request->input('subject_type'),
            'status'    =>  $request->input('status'),
        ]);

        $subject->classrooms()->sync($request->input('classroom'));

        return $this->respond([
            'message' => 'A Subject was updated successfully',
            'Subject' => new SubjectResource($subject)
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

    /**
     * Display a listing of the resource.
     *
     */
    public function types()
    {
        if (Cache::has('typeName')){
            return $this->respond(['typeNameOptions' => Cache::get('typeName')]);
        }

        $types =  SubjectType::select(['id', 'name'])
            ->get();

        Cache::put('typeName', $types, now()->addDay());

        return $this->respond(['typeNameOptions' => Cache::get('typeName')]);
    }
}
