<?php
/**
 * Created by PhpStorm.
 * User: Nasser-allah
 * Date: 5/28/2019
 * Time: 6:39 PM
 */

namespace App;


use Illuminate\Support\Facades\Redis;

trait RecordVisits
{

    public function resetVisits()
    {
        Redis::del($this->visitsCacheKey());

        return $this;
    }

    public function recordVisits()
    {
        Redis::incr($this->visitsCacheKey());

        return $this;
    }

    /**
     * @return string
     */
    protected function visitsCacheKey(): string
    {
        return "threads.{$this->id}.visits";
    }

    public function visits()
    {
        return Redis::get($this->visitsCacheKey()) ?? 0;
    }
}
