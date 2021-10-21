<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Requests\CreateClassroomRequest;
use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;

class AdminClassController extends RespondsWithHttpStatusController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $classrooms = QueryBuilder::for(Classroom::class)
            ->withTrashed()
            ->defaultSort('-created_at')
            ->allowedSorts(['name','status'])
            ->allowedFilters(['name'])
            ->jsonPaginate()
            ->appends($request->query());

        return $this->respond([
            'classrooms' =>  ClassroomResource::collection($classrooms)->response()->getData(true)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateClassroomRequest $request)
    {
        $classroom = Classroom::create([
            'name'  =>  $request->input('name'),
            'session_id'  =>  $request->input('session'),
        ]);

        return $this->responseCreated([
            'classroom' => new ClassroomResource($classroom)
        ], 'A classroom was created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $classroom = QueryBuilder::for(Classroom::where('id', $id))
            ->allowedIncludes(['subjects'])
            ->firstOrFail();

        return $this->respond([
            'classroom' =>  new ClassroomResource($classroom->load('subjects'))
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
                'name'  => ['required', 'string',Rule::unique('classrooms')->ignore($id)],
                'session'  => ['required', 'exists:sessions', Rule::unique('classrooms')->ignore($id)],
            ],
            [
                '*.required' => 'The :attribute field is required',
                '*.exists' => 'The :attribute does not exist',
            ]
        );

        $classroom =  Classroom::query()
                    ->where('id', $id)
                    ->firstOrFail();

        $classroom->update([
            'name'      =>  $request->input('name'),
            'session_id'  =>  $request->input('session_id')
        ]);

        return $this->respond([
            'message' => 'A classroom was updated successfully',
            'classroom' => new ClassroomResource($classroom)
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
        $classroom = Classroom::query()
            ->where('id', $id)
            ->withTrashed()
            ->firstOrFail();

        $classroom->update(['status'    => 'inactive']);

        $classroom->delete();

        return $this->responseOk(['message' => 'A classroom was deleted successfully']);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {
        $classroom = Classroom::query()
            ->where('id', $id)
            ->withTrashed()
            ->firstOrFail();

        $classroom->update(['status'    => 'active']);

        $classroom->restore();

        return $this->respond([
            'message' => 'A classroom was restore successfully',
            'classroom' => new ClassroomResource($classroom)
        ]);
    }
}
