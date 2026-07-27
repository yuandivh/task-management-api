<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Tasks;
use Illuminate\Http\Request;

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
        $tasks = $project->tasks()->with('project')
        ->when($request->search, function ($query) use ($request){
            $query->where("title","like","%".$request->search."%")
            ->orWhere("status","like","%".$request->search."%");

        })
        ->when($request->status, function ($query) use ($request){
            $query->where("status","like","%".$request->status."%");
        })
        ->orderBy('due_date')
        ->paginate(10);

        // return TaskResource::collection($tasks);

        return response()->json([
            "tasks"=>$tasks
        ],200);
    }

    public function show(Request $request, $task_id){
        // $project = $request->user()->projects()->find($project_id);
        // if(!$project){
        //     return response()->json([
        //         "message"=>"Project not found"
        //     ],404);
        // }

        $task = Tasks::with('project')->find($task_id);
        if(!$task){
            return response()->json([
                "message"=>"Task not found"
            ],404);
        }

        $this->authorize('view',$task);

        return new TaskResource($task);
        // return response()->json([
        //     "task"=>$task
        // ],200);
    }

    public function store(StoreTaskRequest $request,$project_id){
        // $request->validate([
        //     "title"=>"required",
        //     "description"=>"required",
        //     "status"=>"sometimes|in:pending,in_progress,completed",
        //     "due_date"=>"sometimes|date"
        // ]);
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
            "data"=>$task
        ],201);
    }

    public function update(UpdateTaskRequest $request,$task_id){
        // $request->validate([
        //     "title"=>"sometimes|required|string",
        //     "description"=>"sometimes|nullable|string",
        //     "status"=>"sometimes|in:pending,in_progress,completed",
        //     "due_date"=>"sometimes|date"
        // ]);
        // $project=$request->user()->projects()->find($project_id);
        // if(!$project){
        //     return response()->json([
        //         "message"=>"Project not found"
        //     ],404);
        // }
        $task = Tasks::find($task_id);
        if(!$task){
            return response()->json([
                "message"=>"Task not found"
            ],404);
        }
        $this->authorize('update',$task);
        $task->update([
            "title"=>$request->title ?? $task->title,
            "description"=>$request->description,
            "status"=>$request->status ?? $task->status,
            "due_date"=>$request->due_date ?? $task->due_date
        ]);
        return response()->json([
            "message"=>"Task updated successfully",
            "data"=>$task
        ],200);
    }

    public function destroy(Request $request,$task_id){
        // $project = $request->user()->projects()->find($project_id);
        // if(!$project){
        //     return response()->json([
        //         "message"=>"Project not found"
        //     ],404);
        // }
        $task = Tasks::find($task_id);
        if(!$task){
            return response()->json([
                "message"=>"Task not found"
            ],404);
        }
        $this->authorize('delete',$task);
        $task->delete();
        return response()->json([
            "message"=> "Task deleted successfully",
        ],200);
    }
}
