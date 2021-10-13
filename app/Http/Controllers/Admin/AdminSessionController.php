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
            ->defaultSort('-created_at')
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
     * @param Session $session
     * @return JsonResponse
     */
    public function show(Session $session)
    {
        return $this->respond(['session' => new SessionResource($session->load(['classrooms','terms']))]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSessionRequest $request
     * @param Session $session
     * @return JsonResponse
     */
    public function update(UpdateSessionRequest $request, Session $session)
    {
        $session->update([
            'name' => $request->input('name'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'duration' => generateDuration($request->start_date,$request->end_date),
        ]);

        return $this->respond([
            'message' => 'A Session was updated successfully',
            'session' => new SessionResource($session->load(['classrooms','terms']))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Session $session
     * @return JsonResponse
     */
    public function destroy(Session $session)
    {
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
        $session =  Session::query()
            ->where('id', $id)
            ->withTrashed()
            ->firstOrFail();

        if ($session->deleted_at == null){
            throw ValidationException::withMessages(['message' => 'session is already restore and active']);
        }

        $session->restore();

        $session->update(['status'  => 'active']);

        return $this->respond([
            'message' => 'Session was restore successfully',
            'session' => new SessionResource($session->load(['classrooms','terms']))
        ]);
    }
}
