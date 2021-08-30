<?php

namespace App\Http\Controllers\SiteOwner;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Resources\ModuleResource;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModuleController extends RespondsWithHttpStatusController
{
    public function index(){
        $modules = Module::latest()->paginate(10);
        return  $this->respond([
           'modules' => ModuleResource::collection($modules)->response()->getData(true)
        ]);
    }

    public function store(Request $request){

        $request->validate([
            'name' => ['required','string','unique:modules,name'],
            'price' => ['required','numeric'],
            'condition'  => ['required','string']
        ]);

       $module = Module::create([
            'name'      => $request->name,
            'price'     => $request->price,
            'condition' => $request->condition,
            'status'    => StatusEnum::ACTIVE
        ]);

        return  $this->responseCreated([
           'module' => new ModuleResource($module)
        ], 'Module created successfully');
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


        return  $this->responseCreated([
            'module'    => new ModuleResource($module)
        ], 'Module Updated Successfully');
    }

    public function show(Module $module){
        return  $this->respond([
            'modules' => new ModuleResource($module)
        ]);
    }

    public function destroy(Module $module){
        $module->delete();
        return  $this->responseOk([
            'message' => 'Module deleted successfully.',
        ]);
    }
}
