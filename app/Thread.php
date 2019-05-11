<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Thread extends Model
{
    use RecordActivity;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

        self::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }

    protected $fillable = ['title', 'body', 'user_id', 'channel_id'];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    public function path()
    {
        return "/threads/{$this->channel->slug}/$this->id";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        // Prepare notifications for all subscribers.
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);

        return $reply;
    }

    public function addReplies($replies)
    {
        foreach ($replies as $reply) {
            $this->addReply($reply);
        }
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        return $this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
    }

    protected function isSubscribedTo($userId = null)
    {
        return $this->subscriptions()->where('user_id', $userId ?: auth()->id())->exists();
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->isSubscribedTo();
    }
}
