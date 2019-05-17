<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoriteable, RecordActivity;

    protected static function boot()
    {
        parent::boot();

//        self::created(function ($reply) {
//            $reply->thread->increment('replies_count');
//        });
//
//        self::deleted(function ($reply) {
//            $reply->thread->decrement('replies_count');
//        });
    }

    protected $fillable = ['body', 'user_id'];

    protected $with = ['owner', 'favorites'];

    protected $appends = ['favorites_count', 'is_favorited'];

    protected $touches = ['thread'];

    public function setBodyAttribute($value)
    {
        $pattern = '/@([\w\-]+)/';

        $this->attributes['body'] = preg_replace($pattern, '<a href="/profiles/$1">$0</a>', $value);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoriteable');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function path()
    {
        return $this->thread->path() . '#reply-' . $this->id;
    }

    public function wasJustPublished($seconds = 60)
    {
        return Carbon::now()->diffInSeconds($this->created_at) <= $seconds;
    }

    public function mentionedUserNames()
    {
        $pattern = '/@([\w\-]+)/';

        preg_match_all($pattern, $this->body, $matches);

        return $matches[1];
    }

}
