<?php


namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_search_threads()
    {
        config(['scout.driver' => 'algolia']);

        $search = 'foobar';

        factory('App\Thread', 2)->create();
        factory('App\Thread', 2)->create(['body' => "A thread with the {$search} term."]);

        // Give time to Algolia to index the records
        do {
            sleep(.25);

            $results = $this->withoutExceptionHandling()
                ->getJson("/threads/search?q={$search}")->json()['data'];
        } while (empty($results));

        $this->assertCount(2, $results);

        // Remove the added threads from Algolia.
        Thread::latest()->take(4)->unsearchable();
    }
}
