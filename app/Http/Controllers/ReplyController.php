<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReplyRequest;
use App\Reply;
use App\Rules\SpamFree;
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
     * @return \Illuminate\Http\Response
     */
    public function store($channelId, Thread $thread, StoreReplyRequest $request)
    {
        $data = $request->validated();

        $reply = $thread->addReply([
            'body' => $data['body'],
            'user_id' => \Auth::id()
        ])->load('owner');

        return $reply;
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

        try {
            $data = $this->validate($request, [
                'body' => ['required', 'string', new SpamFree],
            ]);

            $reply->update([
                'body' => $data['body']
            ]);
        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422);
        }

        return response([], 200);
    }
}
