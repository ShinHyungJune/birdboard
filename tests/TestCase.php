<?php

namespace Tests;

use App\Project;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Setup\ProjectFactory;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $projectFactory;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->projectFactory = new ProjectFactory();
    }

    protected function signIn($user = null)
    {
        $user = $user ?: factory(User::class)->create();

        $this->actingAs($user);

        return $user;
    }
}
