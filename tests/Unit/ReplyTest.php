<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_an_owner()
    {
        $reply = factory('App\Reply')->create();

        $this->assertInstanceOf('App\User', $reply->owner);
    }
    
    /** @test */
    public function it_knows_if_it_was_just_published()
    {
        $reply = factory('App\Reply')->create();

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = now()->subMinutes(2);

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = factory('App\Reply')->create([
            'body' => '@Jane try to use @John is solution'
        ]);

        $this->assertEquals(['Jane', 'John'], $reply->mentionedUserNames());
    }

    /** @test */
    public function it_wraps_mentioned_usernames_in_the_body_within_anchor_tags()
    {
        $reply = new \App\Reply([
            'body' => 'Hi, @Oussama.'
        ]);

        $this->assertEquals('Hi, <a href="/profiles/Oussama">@Oussama</a>.', $reply->body);
    }

    /** @test */
    public function it_knows_if_it_is_the_best_reply()
    {
        $reply = factory('App\Reply')->create();

        $this->assertFalse($reply->isBest());

        $reply->thread->update([
            'best_reply_id' => $reply->id
        ]);

        $this->assertTrue($reply->fresh()->isBest());
    }
}
