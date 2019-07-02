<?php


namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_administrators_may_not_lock_threads()
    {
        $this->signIn();

        $thread = factory('App\Thread')->create([
            'user_id' => auth()->id()
        ]);

        $this->post(route('lock-threads.store', $thread))
            ->assertStatus(403);

        $this->assertFalse($thread->fresh()->is_locked);
    }

    /** @test */
    public function administrators_can_lock_threads()
    {
        $this->signIn(
            factory('App\User')->state('administrator')->create()
        );

        $thread = factory('App\Thread')->create();

        $this->withoutExceptionHandling()
            ->post(route('lock-threads.store', $thread));

        $this->assertTrue($thread->fresh()->is_locked);
    }

    /** @test */
    public function administrators_can_unlock_threads()
    {
        $this->signIn(
            factory('App\User')->state('administrator')->create()
        );

        $thread = factory('App\Thread')->state('locked')->create();

        $this->withoutExceptionHandling()
            ->delete(route('lock-threads.destroy', $thread));

        $this->assertFalse($thread->fresh()->is_locked);
    }

    /** @test */
    public function once_locked_a_thread_may_not_receive_new_replies()
    {
        $thread = factory('App\Thread')->create();
        $thread->lock();

        $this->signIn();

        $this->postJson($thread->path() . '/replies', [
            'body' => 'leaving a reply',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }
}
