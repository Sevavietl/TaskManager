<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Events\TaskCreated;

class Task extends Model
{
    protected $guarded = [];

    protected $events = [
        'created' => TaskCreated::class,
    ];

    public function getDoneAttribute($value)
    {
        return boolval($value);
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function pushSelfIdToProjectTasksOrder()
    {
        $project = $this->project;
        $project->tasks_order = $project->tasks_order ?: [] + [$this->id];
        $project->save();
    }
}
