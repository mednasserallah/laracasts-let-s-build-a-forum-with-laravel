<?php


namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_adminstrator_can_lock_any_thread()
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
