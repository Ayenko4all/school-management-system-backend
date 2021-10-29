<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Requests\CreateSessionRequest;
use App\Http\Requests\UpdateSessionRequest;
use App\Http\Resources\SessionResource;
use App\Models\Session;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\QueryBuilder\QueryBuilder;

class AdminSessionController extends RespondsWithHttpStatusController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $sessions = QueryBuilder::for(Session::class)
            ->withTrashed()
            ->defaultSort('-created_at')
            ->allowedSorts(['name','start_date','end_date','status'])
            ->allowedFilters(['name'])
            ->jsonPaginate()
            ->appends($request->query());

        return $this->respond(['sessions' =>  SessionResource::collection($sessions)->response()->getData(true)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateSessionRequest $request
     * @return JsonResponse
     */
    public function store(CreateSessionRequest $request)
    {
        $session = Session::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration' => generateDuration($request->start_date,$request->end_date),
        ]);

        Return $this->responseCreated([
            'session' =>  new SessionResource($session)
        ], 'A session was created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $session = QueryBuilder::for(Session::where('id', $id))
            ->allowedIncludes(['classrooms','terms'])
            ->firstOrFail();

        $this->authorize('view', $session);

        return $this->respond(['session' => new SessionResource($session)]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSessionRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdateSessionRequest $request, $id)
    {
        $session = QueryBuilder::for(Session::where('id', $id))
            ->allowedIncludes(['classrooms','terms'])
            ->firstOrFail();

        $session->update([
            'name' => $request->input('name'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'duration' => generateDuration($request->start_date,$request->end_date),
        ]);

        return $this->respond([
            'message' => 'A Session was updated successfully',
            'session' => new SessionResource($session)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $session = QueryBuilder::for(Session::where('id', $id))
            ->firstOrFail();

        $this->authorize('restore', $session);

        $session->update(['status'  => 'inactive']);

        $session->delete();

        return $this->responseOk(['message' => 'A Session was deleted successfully']);
    }

    /**
     * Restore the deleted resource from storage.
     *
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function restore($id)
    {
        $session = QueryBuilder::for(Session::where('id', $id))
            ->withTrashed()
            ->firstOrFail();

        $this->authorize('restore', $session);

        $session->restore();

        $session->update(['status'  => 'active']);

        return $this->respond([
            'message' => 'Session was restore successfully',
            'session' => new SessionResource($session->load(['classrooms','terms']))
        ]);
    }
}
