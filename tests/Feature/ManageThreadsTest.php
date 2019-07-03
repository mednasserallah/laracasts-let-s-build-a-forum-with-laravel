<?php

namespace Tests\Feature;

use App\Rules\Recaptcha;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app()->singleton(Recaptcha::class, function () {
           return \Mockery::mock(Recaptcha::class, function ($m) {
               $m->shouldReceive('passes')->andReturn(true);
           });
        });
    }

    /** @test */
    public function guests_cannot_see_the_create_thread_page()
    {
        $this->get('/threads/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function guests_may_not_create_threads()
    {
        $thread = factory('App\Thread')->make();

        $this->post('/threads', $thread->toArray())
            ->assertRedirect('/login');
    }
    
    /** @test */
    public function authenticated_users_must_first_confirm_email_address_before_creating_threads()
    {
        $user = factory('App\User')->create(['email_verified_at' => null]);

        $this->publishThread(['body' => null], $user)
            ->assertRedirect('/email/verify');
    }
    
    /** @test */
    public function a_thread_requires_a_unique_slug()
    {
        $this->signIn();

        $thread = factory('App\Thread')->create([
            'title' => 'Thread Title',
            'slug' => 'thread-title'
        ]);

        $this->assertEquals('thread-title', $thread->slug);

        $this->post(route('threads.store'), $thread->toArray() + ['g-recaptcha-response' => 'token']);

        $this->assertTrue(Thread::whereSlug('thread-title-2')->exists());
    }
    
    /** @test */
    public function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
    {
        $this->signIn();

        $thread = factory('App\Thread')->create([
            'title' => 'Thread Title 2019',
            'slug' => 'thread-title-2019'
        ]);

        $this->post(route('threads.store'), $thread->toArray() + ['g-recaptcha-response' => 'token']);

        $this->assertTrue(Thread::whereSlug('thread-title-2019-2')->exists());
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = factory('App\Thread')->make();

        $response = $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'token']);

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
    
    /** @test */
    public function guests_cannot_delete_threads()
    {
        $thread = factory('App\Thread')->create();

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->withoutExceptionHandling()
            ->json('DELETE', $thread->path())
            ->assertStatus(204);

        $this->assertDatabaseMissing('threads', [
            'id' => $thread->id
        ]);
    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $this->signIn();

        $thread = factory('App\Thread')->create();

        $this->expectException('Illuminate\Auth\Access\AuthorizationException');

        $this->withoutExceptionHandling()
            ->delete($thread->path());
    }
    
    /** @test */
    public function an_authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = factory('App\Thread')->create([
            'user_id' => auth()->id()
        ]);

        $reply = factory('App\Reply')->create([
            'thread_id' => $thread->id
        ]);

        $this->withoutExceptionHandling()
            ->delete($thread->path());

        $this->assertDatabaseMissing('threads', [
            'id' => $thread->id
        ]);

        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id
        ]);

        $this->assertEquals(0, \App\Activity::count());
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_recaptcha_verification()
    {
        unset(app()[Recaptcha::class]);

        $this->publishThread(['g-recaptcha-response' => 'wrong-recaptcha'])
            ->assertSessionHasErrors('g-recaptcha-response');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overrides = [], $user = null)
    {
        $user ? $this->signIn($user) : $this->signIn();

        $thread = factory('App\Thread')->make($overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
