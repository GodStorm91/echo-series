<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $fillable = array('body', 'project_id');
 
    public function project(){
        return $this->belongTo('App\Project');
    }
}
