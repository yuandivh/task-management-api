<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;

class ProjectController extends Controller
{
    //
    public function index(Request $request){
        $project = auth()->user()->projects()->with(["tasks","user"]);
        if($request->search){
            $project->where("name","like","%".$request->search."%");
        }
        $project = $project->paginate($request->per_page ?? 20);
        return ProjectResource::collection($project);

        // Paginate tanpa pakai Resource
        // return response()->json($project,200);
    }
    public function store(StoreProjectRequest $request)
    {
        // $request->validate([
        //     "name"=>"required",
        //     "description"=>"required"
        // ]);

        $project = $request->user()->projects()->create([
            "name"=>$request->name,
            "description"=>$request->description
        ]);

        return response()->json([
            "message"=>"Project created successfully",
            "data"=>$project
        ],201);
    }

    public function show(Request $request, $id){
        $project = $request->user()->projects()->with(["tasks","user"])->find($id);
        if(!$project){
            return response()->json([
                "message"=>"Project not found"
            ],404);
        }
        return new ProjectResource($project);
        // return response()->json([
        //     "project"=>$project
        // ],200);
    }

    public function update(UpdateProjectRequest $request, $id){
        // $request->validate([
        //     "name"=>"required",
        //     "description"=>"sometimes"
        // ]);
        $project = $request->user()->projects()->find($id);
        if(!$project){
            return response()->json([
                "message"=>"Project not found"
            ],404);
        }
        $name = $request->name;
        $description = $request->description;
        if(!$description){
            $description = $project->description;
        }
        $project->update([
            "name"=>$name,
            "description"=>$description
        ]);
        return response()->json([
            "message"=>"Project updated successfully",
            "data"=>$project
        ]);
    }

    public function destroy(Request $request,$id){
        $project = $request->user()->projects()->find($id);
        if(!$project){
            return response()->json([
                "message"=>"Project not found"
            ],404);
        }
        $project->delete();
        return response()->json([
            "message"=>"Project deleted successfully"
        ],200);
    }
}
