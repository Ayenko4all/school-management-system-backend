<?php

namespace App\Http\Controllers\SiteOwner;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Resources\StateResource;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StateController extends RespondsWithHttpStatusController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $states = State::with('lgaAreas')->latest()->paginate(10);
        return  $this->respond([
            'locations' => StateResource::collection($states)->response()->getData(true)
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'state'     =>  ['required', 'string', 'unique:states,state'],
        ]);

        $state = State::create([
            'state'     =>  $request->state,
            'status'    =>  StatusEnum::ACTIVE
        ]);

        return  $this->responseCreated([
            'state' => new StateResource($state)
        ], 'State created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  $state
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(State $state)
    {
        return $this->respond([
            'state' => new StateResource($state->load('lgaAreas'))
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
     * @param  $state
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, State $state)
    {
        $request->validate([
            'state'     =>  ['required', 'string', Rule::unique('states', 'state')->ignore($state->id)],
            'status'    =>  ['required', 'string']
        ]);

        $state->update([
            'state'     =>  $request->state,
            'status'    =>  $request->status
        ]);

        return  $this->responseCreated([
            'state' => new StateResource($state->load('lgaAreas'))
        ], 'State updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $state
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(State $state)
    {
        $state->delete();

        return  $this->responseOk((string)['message' => 'Location deleted successfully.']);
    }
}
