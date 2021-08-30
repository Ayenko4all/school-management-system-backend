<?php

namespace App\Http\Controllers\SiteOwner;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Resources\SchoolTypeResource;
use App\Models\SchoolType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolTypeController extends RespondsWithHttpStatusController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $schoolTypes = SchoolType::latest()->paginate(10);
        return  $this->respond([
            'schoolTypes' => SchoolTypeResource::collection($schoolTypes)->response()->getData(true)
        ]);
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

        return $this->responseCreated([
            'schoolType' => new SchoolTypeResource($schoolType)
        ], 'Type created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  $schoolType
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(SchoolType $schoolType)
    {
        return  $this->respond([
            'schoolType' => new SchoolTypeResource($schoolType)
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

        return $this->responseCreated([
            'data'   => ['type' => new SchoolTypeResource($schoolType)]
        ], 'Type updated successfully.');
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
        return  $this->responseOk([
            'message' => 'Type deleted successfully.',
        ]);
    }
}
