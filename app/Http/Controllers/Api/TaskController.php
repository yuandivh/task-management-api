<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    //
    public function index(Request $request,$project_id){
        $project = $request->user()->projects()->find($project_id);
        if(!$project){
            return response()->json([
                "message"=>"Project not found"
            ],404);
        }
        $tasks = $project->tasks()->with('project')->get();
        return TaskResource::collection($tasks);

        // return response()->json([
        //     "tasks"=>$tasks
        // ],200);
    }

    public function show(Request $request,$project_id,$task_id){
        $project = $request->user()->projects()->find($project_id);
        if(!$project){
            return response()->json([
                "message"=>"Project not found"
            ],404);
        }
        $task = $project->tasks()->with('project')->find($task_id);
        if(!$task){
            return response()->json([
                "message"=>"Task not found"
            ],404);
        }
        return new TaskResource($task);
        // return response()->json([
        //     "task"=>$task
        // ],200);
    }

    public function store(Request $request,$project_id){
        $request->validate([
            "title"=>"required",
            "description"=>"required",
            "status"=>"sometimes|in:pending,in_progress,completed",
            "due_date"=>"sometimes|date"
        ]);
        $project = $request->user()->projects()->find($project_id);
        if(!$project){
            return response()->json([
                "message"=>"Project not found"
            ],404);
        }
        $task = $project->tasks()->create([
            "title"=>$request->title,
            "description"=>$request->description,
            "status"=>$request->status,
            "due_date"=>$request->due_date
        ]);
        return response()->json([
            "message"=>"Task created successfully",
            "task"=>$task
        ],201);
    }

    public function update(Request $request,$project_id,$task_id){
        $request->validate([
            "title"=>"sometimes|required|string",
            "description"=>"sometimes|nullable|string",
            "status"=>"sometimes|in:pending,in_progress,completed",
            "due_date"=>"sometimes|date"
        ]);
        $project=$request->user()->projects()->find($project_id);
        if(!$project){
            return response()->json([
                "message"=>"Project not found"
            ],404);
        }
        $task = $project->tasks()->find($task_id);
        if(!$task){
            return response()->json([
                "message"=>"Task not found"
            ],404);
        }
        $task->update([
            "title"=>$request->title ?? $task->title,
            "description"=>$request->description,
            "status"=>$request->status ?? $task->status,
            "due_date"=>$request->due_date ?? $task->due_date
        ]);
        return response()->json([
            "message"=>"Task updated successfully",
            "task"=>$task
        ],200);
    }

    public function destroy(Request $request, $project_id,$task_id){
        $project = $request->user()->projects()->find($project_id);
        if(!$project){
            return response()->json([
                "message"=>"Project not found"
            ],404);
        }
        $task = $project->tasks()->find($task_id);
        if(!$task){
            return response()->json([
                "message"=>"Task not found"
            ],404);
        }
        $task->delete();
        return response()->json([
            "message"=> "Task deleted successfully",
        ],200);
    }
}
