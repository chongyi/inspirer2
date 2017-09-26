<?php

use App\Schema\Contents\Models\Content;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Content::class, function (Faker $faker) {
    return [
        'title'       => $faker->sentence(rand(3, 6)),
        'keywords'    => implode(',', $faker->words(rand(4, 7))),
        'description' => $faker->text(rand(40, 200)),
    ];
});
