<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_reply_that_is_not_by_the_current_user()
    {
        $thread = factory('App\Thread')->create()->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply(
            factory('App\Reply')->raw([
                'thread_id' => null,
                'user_id' => auth()->id()
            ])
        );

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply(
            factory('App\Reply')->raw([
                'thread_id' => null,
            ])
        );

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }
    
    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
        factory(\Illuminate\Notifications\DatabaseNotification::class)
            ->state('auth')
            ->create();

        $response = $this->withoutExceptionHandling()
            ->getJson(route('profile.notifications', [auth()->user()->name]))
            ->json();

        $this->assertCount(1, $response);
    }
    
    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {
        factory(\Illuminate\Notifications\DatabaseNotification::class)
            ->state('auth')
            ->create();

        tap(auth()->user(), function ($user) {
            $this->assertCount(1, $user->unreadNotifications);

            $notificationId = $user->unreadNotifications->first()->id;
            $url = route('profile.notifications.destroy', [$user->name, $notificationId]);

            $this->withoutExceptionHandling()
                ->delete($url);

            $this->assertCount(0, $user->fresh()->unreadNotifications);
        });
    }
}
