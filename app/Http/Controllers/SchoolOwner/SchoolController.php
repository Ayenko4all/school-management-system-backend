<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Actions\CreatePayStackTransactionAction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Requests\SchoolSetUpFormRequest;
use App\Http\Resources\SchoolResource;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use App\Actions\CreateOwnerAction;

class SchoolController extends RespondsWithHttpStatusController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $schools = School::with(['user','school_type','directors'])->latest()->paginate(10);
        //dd($schools);
       // $this->authorize('view', $schools);

        return  $this->respond([
            'schools' => SchoolResource::collection($schools)->response()->getData(true)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param School $school
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(School $school)
    {
        $this->authorize('view', $school);

        $school->load(['user','school_type','directors']);

        return  $this->respond([
            'school' =>  new SchoolResource($school)
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
