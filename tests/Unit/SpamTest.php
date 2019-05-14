<?php


namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpamTest extends TestCase
{
    use RefreshDatabase;

    private $spam;

    protected function setUp(): void
    {
        parent::setUp();

        $this->spam = new Spam();
    }

    /** @test */
    public function it_checks_for_invalid_keywords()
    {
        $this->assertFalse($this->spam->detect('Innocent body here'));

        $this->expectException(\Exception::class);

        $this->spam->detect('Yahoo customer support');

    }
    
    /** @test */
    public function it_checks_for_any_key_being_held_down()
    {
        $this->assertFalse($this->spam->detect('Hello, Words!'));

        $this->expectException(\Exception::class);

        $this->spam->detect('Hello worddddddds');
    }
}
