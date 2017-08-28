<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

use App\Project;

class ProjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::owned()->with('tasks')->get();

        return view('home', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100'
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 422);
        }

        $project = Project::create([
            'title' => request('title'),
            'user_id' => auth()->id(),
        ]);

        return response($project, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100'
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 422);
        }

        $project->title = request('title');
        $project->save();

        return response($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Project::destroy($id);

        return response(null, 204);
    }
}
