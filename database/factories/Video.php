<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Channel;
use App\Video;
use Faker\Generator as Faker;

$factory->define(Video::class, function (Faker $faker) {
    return [
        'channel_id' => function () {
            return factory(Channel::class)->create()->id;
        },
        'views' => $faker->numberBetween(1, 1000),
        'thumbnail' => $faker->imageUrl(),
        'title' => $faker->sentence(4),
        'description' => $faker->sentence(10),
        'path' => $faker->word,
    ];
});