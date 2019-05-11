<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;

class ThreadFilter extends Filter
{

    protected $filters = ['by', 'popular', 'unanswered'];

    /**
     * Filter the query by a given username.
     *
     * @param string $username
     * @return mixed
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    public function popular()
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count', 'desc');
    }

    public function unanswered()
    {
        return $this->builder->has('replies', 0);
//        return $this->builder->having('replies_count', 0);
//        return $this->builder->where('replies_count', 0);
    }
}
