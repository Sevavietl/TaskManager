<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Project;

class ProjectManipulationTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test 
     */
    public function a_user_can_create_project()
    {   
        $this->signIn()
            ->post('/projects', ($project = make('App\Project', ['user_id' => auth()->id()]))->toArray())
            ->assertStatus(201)
            ->assertJson($project->toArray());
            
        $this->assertEquals($project->title, Project::owned()->first()->title);
    }

    /**
     * @test 
     */
    public function a_project_requires_a_title()
    {
        $this->signIn()
            ->post(
                '/projects',
                ($project = make('App\Project', [
                    'title' => null,
                    'user_id' => auth()->id()])
                )->toArray()
            )
            ->assertStatus(422)
            ->assertJson(['title' => ['The title field is required.']]);
    }

    /**
     * @test 
     */
    public function a_user_can_delete_a_project()
    {
        $this->signIn();

        $project = create('App\Project', ['user_id' => auth()->id()]);

        $this->call('DELETE', "/projects/{$project->id}")
            ->assertStatus(204);

        $this->assertEmpty(Project::find($project->id));
    }
    
    /**
     * @test 
     */
    public function a_user_can_update_a_project()
    {
        $this->signIn();

        $project = create('App\Project', ['user_id' => auth()->id()]);

        $project->title = 'Foo Bar Baz';

        $this->call('PUT', "/projects/{$project->id}", $project->toArray())
            ->assertStatus(200)
            ->assertJson($project->toArray());

        $this->assertEquals($project->title, Project::find($project->id)->title);
    }

    /**
     * @test 
     */
     public function a_project_cannot_be_updated_without_a_title()
     {
        $this->signIn();
        
        $project = create('App\Project', ['user_id' => auth()->id()]);

        $project->title = null;

        $this->call('PUT', "/projects/{$project->id}", $project->toArray())
            ->assertStatus(422)
            ->assertJson(['title' => ['The title field is required.']]);
     }
}
