<?php

namespace App\Http\Controllers\SiteOwner;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\SchoolTypeResource;
use App\Models\SchoolType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $schoolTypes = SchoolType::latest()->paginate(10);
        return  response()->json([
            'status' => 'success',
            'data' => [
                'schoolTypes' => SchoolTypeResource::collection($schoolTypes)->response()->getData(true)
            ]
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  =>  ['required', 'string'],
        ]);

        $schoolType = SchoolType::create([
            'name'      =>  $request->name,
            'status'    =>  StatusEnum::ACTIVE
        ]);

        return  response()->json([
            'status' => 'success',
            'data' => [
                'schoolType' => new SchoolTypeResource($schoolType)
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @param   $schoolType
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function update(Request $request, SchoolType $schoolType)
    {
        $request->validate([
            'name'  =>  ['required', 'string', Rule::unique('school_types', 'name')->ignore($schoolType->id)],
            'status'    =>  ['required', 'string']
        ]);

        $schoolType->update([
            'name'  =>  $request->name,
            'status'    =>  $request->status
        ]);

        return  response()->json([
            'status' => 'success',
            'body'   => 'Type updated successfully.',
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $schoolType
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(SchoolType $schoolType)
    {
        $schoolType->delete();
        return  response()->json([
            'status' => 'success',
            'body'   => 'Type deleted successfully.',
        ], 200);
    }
}
