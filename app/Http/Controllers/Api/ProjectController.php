<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Projects;
use Illuminate\Http\Request;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use Illuminate\Support\Facades\Cache;

class ProjectController extends Controller
{
    //
    public function index(Request $request){
        $search = $request->search;
        $project = $request->user()->projects()->with(["tasks","user"]);
        if($search){
            $project->where("name","like","%".$search."%");
        }
        $project = $project
        ->orderBy('created_at','desc')
        ->paginate($request->per_page ?? 20);
        return ProjectResource::collection($project);

            // Paginate tanpa pakai Resource
            // return response()->json([
            //     "message"=>"Project retrieved successfully",
            //     "data"=>$project],200);

    }
    public function store(StoreProjectRequest $request)
    {
        $project = $request->user()->projects()->create([
            "name"=>$request->name,
            "description"=>$request->description
        ]);

        Cache::forget('projects');

        return response()->json([
            "message"=>"Project created successfully",
            "data"=>$project
        ],201);
    }

    public function show(Request $request, $id){
        //Coba latihan cache
        $project = Cache::remember("project:user:{$request->user()->id}:{$id}",60,function() use ($request,$id){
            return $request->user()->projects()->with(["tasks","user"])->findOrFail($id)->toArray();
        });
        // $project = $request->user()->projects()->with(["tasks","user"])->findOrFail($id);
        if(!$project){
            return response()->json([
                "message"=>"Project not found"
            ],404);
        }
        // return new ProjectResource($project);
        return response()->json([
            "project"=>$project
        ],200);
    }

    public function update(UpdateProjectRequest $request, $id){
        $project = $request->user()->projects()->findOrFail($id);
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
        Cache::forget("projects");
        Cache::forget("project:user:{$request->user()->id}:{$id}");
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
        Cache::forget('projects');
        Cache::forget("project:user:{$request->user()->id}:{$id}");
        return response()->json([
            "message"=>"Project deleted successfully"
        ],200);
    }

    public function testCache(){
        $projects = Cache::remember('projects',60,function(){
            return Projects::orderBy('created_at','desc')->get()->toArray();
        });
        return response()->json([
            "message"=>"Project retrieved successfully",
            "data"=>$projects
        ],200);
    }
}
