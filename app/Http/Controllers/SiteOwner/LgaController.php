<?php

namespace App\Http\Controllers\SiteOwner;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\LgaAreaResource;
use App\Models\LgaArea;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LgaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $lgaAreas = LgaArea::with(['stateType'])->latest()->paginate(10);
        return  response()->json([
            'status' => 'success',
            'data' => [
                'lgaAreas' => LgaAreaResource::collection($lgaAreas)->response()->getData(true)
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
        $request->validate(
            [
            'lga'     =>  ['required', 'string', 'unique:lga_areas'],
            'state_id'  => ['required', 'exists:states,id'],
            ],
            [
                'state_id.exists' => 'The selected state doest not exist',
            ]
        );

        $lgaArea = LgaArea::create([
            'lga'       =>  $request->lga,
            'state_id'  => $request->state_id,
            'status'    =>  StatusEnum::ACTIVE
        ]);

        return  response()->json([
            'status' => 'success',
            'data' => [
                'lgaArea' => new LgaAreaResource($lgaArea)
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $lgaArea
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(LgaArea $lgaArea)
    {
        return  response()->json([
            'status' => 'success',
            'data' => [
                'lgaArea' => new LgaAreaResource($lgaArea->load('stateType'))
            ]
        ], 200);
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
     * @param  $lgaArea
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, LgaArea $lgaArea)
    {
        $request->validate(
            [
            'lga'       =>  ['required', 'string', Rule::unique('lga_areas', 'lga')->ignore($lgaArea->id)],
            'state_id'  => ['required','exists:states,id'],
            'status'    =>  ['required', 'string']
            ],
            [
                'state_id.exists' => 'The selected state doest not exist',
                'state_id.*' => 'The state field is required',
            ]
            );

        $lgaArea->update([
            'lga'       =>  $request->lga,
            'state_id'  => $request->state_id,
            'status'    =>  $request->status
        ]);

        return  response()->json([
            'status' => 'success',
            'message'=>'Lga updated successfully.',
            'data' => [
                'lgaArea' => new LgaAreaResource($lgaArea->load('stateType'))
            ]
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $lgaArea
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(LgaArea $lgaArea)
    {
        $lgaArea->delete();

        return  response()->json([
            'status' => 'success',
            'body'   => 'Lga area deleted successfully.',
        ], 200);
    }
}
