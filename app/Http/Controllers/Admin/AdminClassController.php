<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;

class AdminClassController extends RespondsWithHttpStatusController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $classrooms = Classroom::query()
                        ->withTrashed()
                        ->get();

        return $this->respond([
            'classroom' =>  ClassroomResource::collection($classrooms)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate(
            [
                'name'  => ['required', 'string', 'unique:classrooms']
            ],
            [
                '*.required' => 'The :attribute field is required',
            ]
        );

        $classroom = Classroom::create([
            'name'  =>  $request->input('name')
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
        $classroom = Classroom::query()
                    ->where('id', $id)
                    ->firstOrFail();
        return $this->respond([
            'classroom' =>  new ClassroomResource($classroom)
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
                'status'  => ['required', 'string']
            ],
            [
                '*.required' => 'The :attribute field is required',
            ]
        );

        $classroom =  Classroom::query()
                    ->where('id', $id)
                    ->firstOrFail();

        $classroom->update([
            'name'      =>  $request->input('name'),
            'status'    =>  $request->input('status'),
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
