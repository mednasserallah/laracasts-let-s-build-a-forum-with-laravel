<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Trending;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Trending $trending
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function show(Request $request, Trending $trending)
    {
        $threads = Thread::search($request['q'])->paginate(20);

        if ($request->expectsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }
}
