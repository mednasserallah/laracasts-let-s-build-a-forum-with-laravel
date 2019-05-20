<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use RefreshDatabase;

    private $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $this->get('/threads')
                ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_a_single_thread()
    {
        $this->get($this->thread->path())
                ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = factory('App\Channel')->create();

        $threadInChannel = factory('App\Thread')->create([
            'channel_id' => $channel->id
        ]);

        $threadNotInChannel = factory('App\Thread')->create();

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_thread_by_any_username()
    {
        $this->signIn();
        $mohamed = factory('App\User')->create(['name' => 'Mohamed']);

        $threadByMohamed = factory('App\Thread')->create(['user_id' => $mohamed->id]);
        $threadNotByMohamed = factory('App\Thread')->create(['user_id' => auth()->id()]);

        $this->get('/threads?by=Mohamed')
            ->assertSee($threadByMohamed->title)
            ->assertDontSee($threadNotByMohamed->title);
    }
    
    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        // Thread without reply -> $this->thread;
        $threadWithNoReplies = $this->thread;

        // Thread with one reply.
        $threadWithTwoReplies = factory('App\Thread')->create([ 'created_at' => new Carbon('-2 minute') ]);
        factory('App\Reply')->create([
            'thread_id' => $threadWithTwoReplies->id
        ]);

        // Thread with three replies.
        $threadWithThreeReplies = factory('App\Thread')->create([ 'created_at' => new Carbon('-1 minute') ]);
        factory('App\Reply', 3)->create([
            'thread_id' => $threadWithThreeReplies->id,
        ]);

        $this->get('threads?popular=1')
            ->assertSeeInOrder([
                $threadWithThreeReplies->title,
                $threadWithTwoReplies->title,
                $threadWithNoReplies->title
            ]);
    }
    
    /** @test */
    public function a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = factory('App\Thread')->create();

        $replies = factory('App\Reply', 2)->create([
            'thread_id' => $thread->id
        ]);

        $response = $this->withoutExceptionHandling()
                        ->getJson($thread->path() . '/replies')->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }
    
    /** @test */
    public function a_user_can_filter_threads_by_those_that_are_unanswered()
    {
        factory('App\Thread', 2)->create();

        factory('App\Reply')->create([
            'thread_id' => $this->thread->id
        ]);

        $threads = $this->withoutExceptionHandling()
                        ->getJson(route('threads.index', ['unanswered' => true]))->json();

        $this->assertCount(2, $threads['data']);
    }
}
