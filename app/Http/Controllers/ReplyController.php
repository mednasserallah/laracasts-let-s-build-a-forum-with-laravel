<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $channelId
     * @param Thread $thread
     * @param  \Illuminate\Http\Request $request
     * @param Spam $spam
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store($channelId, Thread $thread, Request $request, Spam $spam)
    {
        $data = $this->validate($request, [
            'body' => 'required|string',
        ]);

        $spam->detect($data['body']);

        return ($thread->addReply([
            'body' => $data['body'],
            'user_id' => \Auth::id()
        ]))->load('owner');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('delete', $reply);

        $reply->delete();

        return response([], 200);
    }

    public function update(Reply $reply, Request $request)
    {
        $this->authorize('update', $reply);

        $reply->update([
            'body' => $request['body']
        ]);

        return response([], 200);
    }
}
