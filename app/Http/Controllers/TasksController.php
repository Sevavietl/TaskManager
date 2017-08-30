<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;
use Illuminate\Http\Request;

use Validator;

class TasksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'ajax']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Project
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Project $project, Request $request)
    {
        $this->validate($request, [
            'description' => 'required|max:100'
        ]);

        $task = Task::create([
            'description' => request('description'),
            'project_id' => $project->id,
            'done' => request('done', false)
        ]);

        return response($task, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project, Task $task)
    {
        $this->validate($request, [
            'description' => 'required|max:100'
        ]);

        $task->description = request('description');
        $task->done = request('done');
        $task->deadline = request('deadline');
        $task->save();

        return response($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, Task $task)
    {
        $task->delete();

        return response(null, 204);
    }
}
