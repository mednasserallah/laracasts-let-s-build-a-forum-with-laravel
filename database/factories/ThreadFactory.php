<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Thread;
use Faker\Generator as Faker;

$factory->define(Thread::class, function (Faker $faker) {

    $title =  $faker->sentence;

    return [
        'title' => $title,
        'slug' => \Illuminate\Support\Str::slug($title),
        'body' => $faker->paragraph,
        'channel_id' => function() {
            return factory(\App\Channel::class)->create()->id;
        },
        'user_id' => function() {
            return factory(\App\User::class)->create()->id;
        }
    ];
});
