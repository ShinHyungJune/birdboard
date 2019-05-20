<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $project = factory(Project::class)->create(['user_id' => auth()->id()]);

        $task = "빨래 널자";

        $this->post('/tasks',['body' => $task, 'project_id' => $project->id]);

        $this->get($project->path())
            ->assertSee($task);
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $this->signIn();

        $attributes = factory(Task::class)->raw(['body' => '']);

        $this->post('/tasks',$attributes)->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_user_can_add_task_only_their_projects()
    {
        $user = factory(User::class)->create();

        $other = factory(User::class)->create();

        $this->signIn($user);

        $project = factory(Project::class)->create(['user_id' => $other->id]);

        $attributes = factory(Task::class)->raw(['project_id' => $project->id]);

        $this->post('/tasks',$attributes)->assertStatus(403);
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $this->signIn();

        $project = factory(Project::class)->create(['user_id' => auth()->id()]);

        $task = factory(Task::class)->create(['project_id' => $project->id]);

        $result = $this->patch('/tasks/'.$task->id, [
            'body' => 'changed',
            'completed' => true,
            'project_id' => $task->project_id
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true,
        ]);
    }

    /** @test */
    public function a_user_can_update_only_their_task()
    {
        $user = factory(User::class)->create();

        $other = factory(User::class)->create();

        $this->signIn($user);

        $project = factory(Project::class)->create(['user_id' => $other->id]);

        $task = factory(Task::class)->create(['project_id' => $project->id]);

        $this->patch('/tasks/'.$task->id, ["body"=>"asd"])->assertStatus(403);
    }
}
