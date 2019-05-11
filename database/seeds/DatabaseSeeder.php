<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (App::isLocal()) {
            $threads = factory('App\Thread', 50)->create();

            $threads->each(function ($thread) {
               $thread->addReplies(
                   factory('App\Reply', rand(0, 22))->raw(['thread_id' => null])
               );
            });

            // Creating me :)
            factory('App\User')->create([
                'name' => 'Nasmed',
                'email' => 'med.nasserallah@gmail.com'
            ]);
        }
    }
}
