<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class LockThreadController extends Controller
{
    /**
     * Lock the thread.
     *
     * @param Thread $thread
     * @return void
     */
    public function store(Thread $thread)
    {
        $thread->lock();
    }

    /**
     * Unlock the thread.
     *
     * @param Thread $thread
     * @return void
     */
    public function destroy(Thread $thread)
    {
        $thread->unlock();
    }
}
