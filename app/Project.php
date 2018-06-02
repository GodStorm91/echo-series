<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    protected $table = 'projects';
    public function tasks(){
        return $this->hasMany('App\Task');
    }

    public function participants(){
        return $this->belongsToMany('App\User', 'project_participants');
    }
}

