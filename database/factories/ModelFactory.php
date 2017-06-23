<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Repositories\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'nickname'       => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(\App\Repositories\Content\ContentEntity\Article::class, function (\Faker\Generator $faker) {
    return [
        'content'       => $faker->realText(rand(1024, 20000)),
        'origin_source' => $faker->boolean ? $faker->url : null,
    ];
});

$factory->define(\App\Repositories\Content\ContentTreeNode::class, function (\Faker\Generator $faker) {
    return [
        'title'       => $faker->words(rand(1, 2), true),
        'keywords'    => implode(',', $faker->words(rand(2, 8))),
        'description' => $faker->paragraph(2),
    ];
});