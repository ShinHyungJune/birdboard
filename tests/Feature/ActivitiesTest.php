<?php

namespace Tests\Feature;

use App\Task;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class activitiesTest extends TestCase
{
    use RefreshDatabase;

    protected $projectFactory;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->projectFactory = new ProjectFactory();
    }

    /** @test */
    public function creating_a_project_generates_activity()
    {
        $project = $this->projectFactory->create();

        $this->actingAs($project->user);

        $this->assertCount(1, $project->activities);

        $this->assertEquals('created', $project->activities[0]->description);
    }

    /** @test */
    function updating_a_project_generates_activity()
    {
        $project = $this->projectFactory->create();

        $project->update(['title' => 'changed']);

        $this->assertCount(2, $project->activities);

        $this->assertEquals('updated', $project->activities->last()->description);
    }

    /** @test */
    function creating_a_new_task_records_activity()
    {
        $project = $this->projectFactory->withTasks(1)->create();

        $this->assertCount(2, $project->activities);

        $activity = $project->activities->last();

        $this->assertEquals('created', $activity->description);
        $this->assertInstanceOf(Task::class, $activity->subject);
    }

    /** @test */
    function completing_a_new_task_records_activity()
    {
        $project = $this->projectFactory->withTasks(1)->create();

        $this->actingAs($project->user)->patch($project->tasks[0]->path(), [
            'body' => 'foober',
            'completed' => true
        ]);

        $this->assertCount(3, $project->activities);

        $activity = $project->activities->last();

        $this->assertEquals('completed', $activity->description);
        $this->assertInstanceOf(Task::class, $activity->subject);
    }
}
