<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Tasks;
class Projects extends Model
{
    //
    protected $fillable = ["name","description","user_id"];
    protected $table = "projects";
    public function tasks(){
        return $this->hasMany(Tasks::class,"project_id","id");
    }

    public function user(){
        return $this->belongsTo(User::class,"user_id","id");
    }
}
