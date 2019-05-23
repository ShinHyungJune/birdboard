<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected $projectFactory;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->projectFactory = new ProjectFactory();
    }

    /** @test */
    function a_user_can_create_a_project()
    {
        // 직접 만든 커스텀 함수임. TestCase.php에다 만들면됨.
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph
        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        // 테이블에서 해당 데이터를 가진 레코드가 있는지 조회
        $this->assertDatabaseHas('projects', $attributes);

        // view에서 데이터가 노출되는지지
        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */
    function a_user_can_view_their_project()
    {
        $this->signIn();

        $project = factory(Project::class)->create(['user_id' => auth()->user()->id]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->signIn();

        $project = factory(Project::class)->create();

        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    function guests_cannot_control_project()
    {
        $project = factory(Project::class)->create();

        $this->get($project->path())->assertRedirect('login');


        $this->get($project->path())->assertRedirect('/login');

        $this->post('/projects', $project->toArray())->assertRedirect('login');


    }

    /** @test */
    function a_project_requires_a_title()
    {
        $this->signIn();

        $attributes = factory(Project::class)->raw(['title' => '']); // db에 저장 안하고 array로 생성

        // title 입력 안했을 때 validation error 기대
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    function a_project_requires_a_body()
    {
        $this->signIn();

        $attributes = factory(Project::class)->raw(['body' => '']);
        // body 입력 안했을 때 validation error 기대
        $this->post('/projects', $attributes)->assertSessionHasErrors('body');
    }

    /** @test */
    function a_user_can_update_a_project()
    {
        $project = factory(Project::class)->create();

        $this->actingAs($project->user);

        $this->get($project->path() . '/edit')->assertStatus(200);

        $this->patch($project->path(), $attributes = ['title' => 'changed', 'body' => 'changed']);

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    function unauthorized_users_cannot_delete_projects()
    {
        $project = $this->projectFactory->create();

        $this->delete($project->path())
            ->assertRedirect('/login');

        $this->signIn();

        $this->delete($project->path())->assertStatus(403);
    }

    /** @test */
    function a_user_can_delete_a_project()
    {
        $this->withoutExceptionHandling();

        $project = $this->projectFactory->create();

        $this->actingAs($project->user)
            ->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

}
