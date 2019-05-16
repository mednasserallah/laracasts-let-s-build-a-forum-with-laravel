<?php


namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $mohamed = factory('App\User')->create(['name' => 'Mohamed']);
        $this->signIn($mohamed);

        $oussama = factory('App\User')->create(['name' => 'Oussama']);

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make([
            'body' => '@Oussama look at this. Also @Anas',
            'thread_id' => $thread->id,
            'user_id' =>  $mohamed->id
        ]);

        $this->postJson($thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $oussama->notifications);

    }
}

