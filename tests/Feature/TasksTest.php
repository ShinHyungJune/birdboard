<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use App\Project;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $this->signIn();

        $project = $this->projectFactory->ownedBy($this->signIn())->withTasks(1)->create();

        $this->post('/tasks',['body' => $project->tasks->first()->body, 'project_id' => $project->id]);

        $this->get($project->path())
            ->assertSee($project->tasks->first()->body);
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

    protected $projectFactory;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->projectFactory = new ProjectFactory();
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $project = $this->projectFactory->ownedBy($this->signIn())->withTasks(1)->create();

        $result = $this->patch('/tasks/'.$project->tasks[0]->id, [
            'body' => 'changed',
            'completed' => true,
            'project_id' => $project->id
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

        $project = $this->projectFactory->ownedBy($other)->withTasks(1)->create();

        $this->patch('/tasks/'.$project->tasks->first()->id, ["body"=>"asd"])->assertStatus(403);
    }
}
