<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;

    private $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function unauthenticated_user_may_not_participate_in_forum_threads()
    {
        $reply = factory('App\Reply')->raw();

        $this->post($this->thread->path() . '/replies', $reply)
            ->assertRedirect('login');;
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn();

        $reply = factory('App\Reply')->make([
            'thread_id' => $this->thread->id
        ]);

        $this->post($this->thread->path() . '/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', [
            'thread_id' => $reply->thread_id,
            'body' => $reply->body
        ]);

        $this->assertEquals(1, $this->thread->replies->count());
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->signIn();

        $reply = factory('App\Reply')->make([
            'body' => null
        ]);

        $this->post($this->thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function guests_cannot_delete_replies()
    {
        $reply = factory('App\Reply')->create();

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->withoutExceptionHandling()
            ->delete('/replies/' . $reply->id);
    }

    /** @test */
    public function unauthorized_users_cannot_delete_replies()
    {
        $this->signIn();

        $reply = factory('App\Reply')->create();

        $this->expectException('Illuminate\Auth\Access\AuthorizationException');

        $this->withoutExceptionHandling()
            ->delete('/replies/' . $reply->id);
    }

    /** @test */
    public function authorized_users_can_delete_replies()
    {
        $this->signIn();

        $reply = factory('App\Reply')->create([
            'user_id' => auth()->id()
        ]);

        $this->withoutExceptionHandling()
            ->delete('/replies/' . $reply->id);

        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id
        ]);

        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */
    public function guests_cannot_update_replies()
    {
        $reply = factory('App\Reply')->create();

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->withoutExceptionHandling()
            ->patch('/replies/' . $reply->id, [
                'body' => 'You\'ve been changed, fool!'
            ]);
    }

    /** @test */
    public function unauthorized_users_cannot_update_replies()
    {
        $this->signIn();

        $reply = factory('App\Reply')->create();

        $this->expectException('Illuminate\Auth\Access\AuthorizationException');

        $this->withoutExceptionHandling()
            ->patch('/replies/' . $reply->id, [
                'body' => 'You\'ve been changed, fool!'
            ]);
    }

    /** @test */
    public function authorized_users_can_update_replies()
    {
        $this->signIn();

        $reply = factory('App\Reply')->create([
            'user_id' => auth()->id()
        ]);

        $this->withoutExceptionHandling()
            ->patch('/replies/' . $reply->id, [
                'body' => 'You\'ve been changed, fool!'
            ]);

        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => 'You\'ve been changed, fool!'
        ]);
    }

    /** @test */
    public function replies_that_contain_spam_may_not_be_created()
    {
        $this->signIn();

        $reply = factory('App\Reply')->create([
            'user_id' => auth()->id(),
            'body' => 'Yahoo Customer Support'
        ]);

        $this->expectException(\Exception::class);

        $this->withoutExceptionHandling()
            ->post($this->thread->path() . '/replies', $reply->toArray());
    }
}
