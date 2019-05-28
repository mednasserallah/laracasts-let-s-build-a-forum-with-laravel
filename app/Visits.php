<?php
/**
 * Created by PhpStorm.
 * User: Nasser-allah
 * Date: 5/28/2019
 * Time: 7:05 PM
 */

namespace App;


use Illuminate\Support\Facades\Redis;

class Visits
{
    protected $thread;

    public function __construct($thread)
    {
        $this->thread = $thread;
    }

    public function reset()
    {
        Redis::del($this->cacheKey());

        return $this;
    }

    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }

    public function count()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    /**
     * @return string
     */
    protected function cacheKey(): string
    {
        return "threads.{$this->thread->id}.visits";
    }
}
