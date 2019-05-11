<?php


namespace Tests\Feature;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_a_profile()
    {
        $user = factory('App\User')->create();

        $this->withoutExceptionHandling()
            ->get("/profiles/{$user->name}")
            ->assertSee($user->name);
    }


    /** @test */
    public function profiles_display_all_threads_created_by_the_associated_user()
    {
        $this->signIn();

        $threads = factory('App\Thread', 2)->create([
            'user_id' => auth()->id()
        ]);

        $this->withoutExceptionHandling()
            ->get('/profiles/' . auth()->user()->name)
            ->assertSee($threads[0]->title)
            ->assertSee($threads[1]->title);
    }
}
