<?php


namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscribeToThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $this->signIn();

        $thread = factory('App\Thread')->create();

        $this->withoutExceptionHandling()
            ->post(route('threads.subscriptions.store', [$thread->channel->slug, $thread->id]));

        $this->assertCount(1, $thread->subscriptions);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_threads()
    {
        $this->signIn();

        $thread = factory('App\Thread')->create();

        $thread->subscribe();

        $this->withoutExceptionHandling()
            ->delete(route('threads.subscriptions.destroy', [$thread->channel->slug, $thread->id]));

        $this->assertDatabaseMissing('thread_subscriptions', [
            'thread_id' => $thread->id,
            'user_id' => auth()->id()
        ]);

        $this->assertCount(0, $thread->subscriptions);
    }
}
