<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Models\Term;
use App\Options\TermOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Schema;

class OptionController extends RespondsWithHttpStatusController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function terms()
    {
        $terms =  defaultOptionNames(TermOption::class);

        return $this->respond(['termOptions' => $terms]);
    }
}
