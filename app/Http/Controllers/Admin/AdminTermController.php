<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Requests\CreateTermRequest;
use App\Http\Requests\UpdateTermRequest;
use App\Http\Resources\SessionResource;
use App\Http\Resources\TermResource;
use App\Models\Session;
use App\Models\Term;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\QueryBuilder\QueryBuilder;

class AdminTermController extends RespondsWithHttpStatusController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $terms = QueryBuilder::for(Term::class)
            ->withTrashed()
            ->defaultSort('-created_at')
            ->allowedFilters(['name'])
            ->jsonPaginate()
            ->appends($request->query());

        return $this->respond(['terms' =>  TermResource::collection($terms)->response()->getData(true)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateTermRequest $request
     * @return JsonResponse
     */
    public function store(CreateTermRequest $request)
    {
        $term = Term::create([
            'name' => $request->input('name'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'session_id' => $request->input('session'),
        ]);

        Return $this->responseCreated([
            'term' =>  new TermResource($term->load('session'))
        ], 'A term was created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param Term $term
     * @return JsonResponse
     */
    public function show(Term $term)
    {
        return $this->respond(['term' => new TermResource($term->load('session'))]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTermRequest $request
     * @param Term $term
     * @return JsonResponse
     */
    public function update(UpdateTermRequest $request, Term $term)
    {
        $term->update([
            'name' => $request->input('name'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'session_id' => $request->input('session'),
        ]);

        return $this->respond([
            'message' => 'A term was updated successfully',
            'term' => new TermResource($term->load('session'))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Term $term
     * @return JsonResponse
     * @throws ValidationException
     */
    public function destroy(Term $term)
    {
        if ($term->deleted_at != null){
            throw ValidationException::withMessages(['message' => 'term is already deleted and inactive']);
        }

        $term->update(['status'  => 'inactive']);

        $term->delete();

        return $this->responseOk(['message' => 'A term was deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function restore($id)
    {
        $term =  Term::query()
            ->where('id', $id)
            ->withTrashed()
            ->firstOrFail();

        if ($term->deleted_at == null){
            throw ValidationException::withMessages(['message' => 'term is already restored and active']);
        }

        $term->restore();

        $term->update(['status' => 'active']);

        return $this->respond([
            'message' => 'Term was restore successfully',
            'term' => new TermResource($term->load('session'))
        ]);
    }
}
