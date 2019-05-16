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
        $thread = factory('App\Thread')->create();

        $mohamed = factory('App\User')->create(['name' => 'Mohamed']);
        $anas = factory('App\User')->create(['name' => 'Anas']);
        $oussama = factory('App\User')->create(['name' => 'Oussama']);
        
        $this->signIn($mohamed);
        $this->replyToThread($thread, $mohamed, '@Oussama look at this. Also @Anas');
        $this->assertCount(1, $oussama->notifications);
        $this->assertCount(1, $anas->notifications);

        $this->signIn($oussama);
        $this->replyToThread($thread, $oussama, 'That is funny, thanks @Mohamed');
        $this->assertCount(1, $mohamed->notifications);
    }

    /**
     * @param $thread
     * @param $user
     * @param $body
     * @return mixed
     */
    protected function replyToThread($thread, $user, $body)
    {
        $reply = factory('App\Reply')->make([
            'body' => $body,
            'thread_id' => $thread->id,
            'user_id' => $user->id
        ]);

        $this->postJson($thread->path() . '/replies', $reply->toArray());
        
        return $reply;
    }
}

