<?php

namespace App\Http\Controllers\SchoolOwner;

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
     *
     * @param SchoolSetUpFormRequest $request
     * @param CreateOwnerAction $createOwnerAction
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(SchoolSetUpFormRequest $request, CreateOwnerAction $createOwnerAction)
    {

        $this->authorize('create', auth()->user());

        $school = School::create([
            'user_id'                   => auth()->id(),
            'school_type_id'            => $request->school_type_id,
            'school_name'               => $request->school_name,
            'school_address'            => $request->school_address,
            'bvn'                       => $request->bvn,
            'city'                      => $request->city,
            'lga'                       => $request->lga,
            'state'                     => $request->state,
            'school_email_address'      => $request->school_email_address,
            'school_telephone_address'  => $request->school_telephone_address,
            'cac_document'              => $request->cac_document ?: null,
        ]);
//app(SchoolOwnerController::class)->store($request,$school->id);
        if ($school){
           list($owner) = $createOwnerAction->execute($request, $school->id);
           //dd($owner);
        }

        $school->modules()->sync($request->modules);

        return $this->responseCreated([
            'school' => new SchoolResource($school->load(['directors', 'modules']))
        ], 'School created successfully');

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
