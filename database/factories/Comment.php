<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Video;
use App\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence(6),
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'video_id' => function () {
            return factory(Video::class)->create()->id;
        },
        'comment_id' => null
    ];
});
