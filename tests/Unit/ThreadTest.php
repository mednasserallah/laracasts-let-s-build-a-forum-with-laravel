<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    public function a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $this->assertInstanceOf('App\Channel', $this->thread->channel);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn();

        $this->thread->subscribe();

        $this->thread->addReply(
            factory('App\Reply')->raw(['thread_id' => null])
        );

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    public function a_thread_can_make_a_string_path()
    {
        $this->assertEquals(
            "/threads/{$this->thread->channel->slug}/{$this->thread->id}",
            $this->thread->path()
        );
    }

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $this->signIn();

        $this->thread->subscribe();

        $this->assertEquals(1, $this->thread->subscriptions()->where('user_id', auth()->id())->count());
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        $this->signIn();

        $this->thread->subscribe();
        $this->thread->unsubscribe();

        $this->assertCount(0, $this->thread->subscriptions);
    }

    /** @test */
    public function a_thread_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $this->signIn();

        $this->assertFalse($this->thread->isSubscribedTo);

        $this->thread->subscribe();

        $this->assertTrue($this->thread->isSubscribedTo);
    }

    /** @test */
    public function a_thread_can_check_if_the_auhtneticated_user_has_read_all_replies()
    {
        $this->signIn();

        $this->assertTrue($this->thread->hasUpdates());

        auth()->user()->seen($this->thread);

        $this->assertFalse($this->thread->hasUpdates());
    }

    /** @test */
    public function a_thread_records_each_visit()
    {
        $this->thread->resetVisits();

        $this->assertSame(0, $this->thread->visits());

        $this->thread->recordVisits();

        $this->assertEquals(1, $this->thread->visits());
    }
}
