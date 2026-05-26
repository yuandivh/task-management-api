<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    //
    public function index(){
        $project = auth()->user()->projects()->with("tasks")->get();
        return response()->json([
            "projects"=>$project
        ],200);
    }
    public function store(Request $request)
    {
        $request->validate([
            "name"=>"required",
            "description"=>"required"
        ]);

        $project = $request->user()->projects()->create([
            "name"=>$request->name,
            "description"=>$request->description
        ]);
        return response()->json([
            "message"=>"Project created successfully",
            "project"=>$project
        ],201);
    }

    public function show(Request $request, $id){
        $project = $request->user()->projects()->with("tasks")->find($id);
        if(!$project){
            return response()->json([
                "message"=>"Project not found"
            ],404);
        }
        return response()->json([
            "project"=>$project
        ],200);
    }

    public function update(Request $request,$id){
        $request->validate([
            "name"=>"required",
            "description"=>"sometimes"
        ]);
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
            "project"=>$project
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
