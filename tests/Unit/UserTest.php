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
    
    /** @test */
    public function a_user_can_determine_their_avatar_path()
    {
        $user = factory('App\User')->create();

        $this->assertEquals('avatars/default.png', $user->avatar());

        $user->avatar_path = 'avatars/me.jpg';

        $this->assertEquals('avatars/me.jpg', $user->avatar());
    }
}

