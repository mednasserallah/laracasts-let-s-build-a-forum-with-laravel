<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Reply;
use Faker\Generator as Faker;

$factory->define(Reply::class, function (Faker $faker) {
    return [
        'body' => $faker->paragraph,
        'user_id' => function() {
            return factory(\App\User::class)->create()->id;
        },
        'thread_id' => function() {
            return factory(\App\Thread::class)->create()->id;
        }
    ];
});
