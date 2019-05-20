<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Task::class, function (Faker $faker) {
    return [
        'project_id' => factory(\App\Project::class)->create(),
        'body' => $faker->sentence,
        'completed' => false
    ];
});
