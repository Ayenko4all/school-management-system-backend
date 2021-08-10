<?php

namespace App\Http\Controllers\SiteOwner;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModuleResource;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModuleController extends Controller
{
    public function index(){
        $modules = Module::latest()->paginate(10);
        return  response()->json([
            'status' => 'success',
            'data' => [
                'modules' => ModuleResource::collection($modules)->response()->getData(true)
            ]
        ], 200);
    }

    public function store(Request $request){

        $request->validate([
            'name' => ['required','string','unique:modules,name'],
            'price' => ['required','numeric'],
            'condition'  => ['required','string'],
            'status'    => ['required', 'string']
        ]);

       $module = Module::create([
            'name' => $request->name,
            'price' => $request->price,
            'condition' => $request->condition,
            'status'    => $request->status
        ]);

        return  response()->json([
            'status' => 'success',
            'data' => [
                'module' => new ModuleResource($module),
            ]
        ], 201);
    }

    public function update(Request $request, Module $module){
        $request->validate([
            'name' => ['required','string',Rule::unique('modules', 'name')->ignore($module->id)],
            'price' => ['required','numeric'],
            'condition'  => ['required','string'],
            'status'    => ['required', 'string']
        ]);


        $module->update([
            'name' => $request->name,
            'price'  => $request->price,
            'condition' => $request->condition,
            'status'    => $request->status
        ]);


        return  response()->json([
            'status' => 'success',
            'body'   => 'Module updated successfully.',
        ], 201);
    }

    public function show(Module $module){
        return  response()->json([
            'status' => 'success',
            'data' => [
                'modules' => new ModuleResource($module)
            ]
        ], 200);
    }
}
