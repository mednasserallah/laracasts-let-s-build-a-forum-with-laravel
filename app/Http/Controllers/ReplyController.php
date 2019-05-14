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
    public function store($channelId, Thread $thread, Request $request)
    {
        $data = $this->validateReply($request);

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

        $data = $this->validateReply($request);

        $reply->update([
            'body' => $data['body']
        ]);

        return response([], 200);
    }

    /**
     * @param Request $request
     * @param Spam $spam
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateReply(Request $request): array
    {
        $data = $this->validate($request, [
            'body' => 'required|string',
        ]);

        resolve(Spam::class)->detect($data['body']);

        return $data;
    }
}
