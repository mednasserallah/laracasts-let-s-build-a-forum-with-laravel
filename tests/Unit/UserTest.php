<?php


namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_fetch_their_most_recent_reply()
    {
        $user = factory('App\User')->create();

        $reply = factory('App\Reply')->create(['user_id' =>  $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }
}

