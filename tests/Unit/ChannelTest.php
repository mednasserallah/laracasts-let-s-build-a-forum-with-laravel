<?php


namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChannelTest extends TestCase
{
    use RefreshDatabase;

    protected $channel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->channel = factory('App\Channel')->create();
    }

    /** @test */
    public function a_channel_consists_of_threads()
    {
        $thread = factory('App\Thread')->create([
            'channel_id' => $this->channel->id
        ]);

        $this->assertTrue($this->channel->threads->contains($thread));
    }

    /** @test */
    public function a_channel_can_make_a_string_path()
    {
        $this->assertEquals(
            "/threads/{$this->channel->slug}",
            $this->channel->path()
        );
    }
}
