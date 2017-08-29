<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];
    
    public function scopeOwned($query)
    {
        return $query->whereUserId(auth()->id());
    }

    public function setTasksOrderAttribute($value)
    {
        $this->attributes['tasks_order'] = json_encode($value);
    }

    public function getTasksOrderAttribute($value)
    {
        return json_decode($value);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
