<?php

namespace App\Http\Controllers;

use App\Reply;

class BestReplyController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Reply $reply
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Reply $reply)
    {
        $this->authorize('update', $reply->thread);

        $reply->thread->markBestReply($reply);
    }
}
