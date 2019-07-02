<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class LockThreadController extends Controller
{
    /**
     * Lock a thread.
     *
     * @param Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function store(Thread $thread)
    {
//        if (! \Auth::user()->isAdmin()) {
//            return response('You do not have permission to lock this thread', 403);
//        }

        $thread->lock();
    }
}
