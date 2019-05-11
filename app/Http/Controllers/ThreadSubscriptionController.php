<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Thread;
use Illuminate\Http\Request;

class ThreadSubscriptionController extends Controller
{
    public function store(Channel $channel, Thread $thread)
    {
        $thread->subscribe();
    }

    public function destroy(Channel $channel, Thread $thread)
    {
        $thread->unsubscribe();
    }
}
