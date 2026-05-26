<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Projects;

class Tasks extends Model
{
    //
    protected $fillable = ["title","description","project_id","status","due_date"];
    protected $table = "tasks";
    public function project(){
        return $this->belongsTo(Projects::class,"project_id","id");
    }
}
