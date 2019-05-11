<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function store(Reply $reply)
    {
        $reply->favorite();

        return response([], 200);
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();

        return response([], 200);
    }
}
