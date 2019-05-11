<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /*
    * Create a user or pass it and authenticate it,
    */
    public function signIn($user = null)
    {
        if (! $user) {
            $user = factory('App\User')->create();
        }

        $this->user = $user;

        $this->actingAs($this->user);

        return $this;
    }
}
