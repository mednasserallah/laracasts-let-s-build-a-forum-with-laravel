<?php

namespace App;

use App\Events\ThreadHasNewReply;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Thread extends Model
{
    use RecordActivity;
    use Searchable;

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

    protected $fillable = ['title', 'body', 'user_id', 'channel_id', 'slug', 'best_reply_id', 'is_locked'];

    protected $casts = [
        'is_locked' => 'boolean',
    ];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($value)
    {
        if (static::whereSlug($slug = Str::slug($value))->exists()) {
            $slug = $this->incrementSlug($slug);
        }

        $this->attributes['slug'] = $slug;
    }

    /**
     * Increment a slug's suffix.
     *
     * @param  string $slug
     * @return string
     */
    protected function incrementSlug($slug, $count = 2)
    {
        $original = $slug;

        while (Static::whereSlug($slug)->exists()) {
            $slug = $original . '-' . $count;
        }

        return $slug;
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/$this->slug";
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

        event(new ThreadHasNewReply($this, $reply));

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

    public function hasUpdates()
    {
        if (! \Auth::check()) {
            return true;
        }

        $key = \Auth::user()->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    /**
     * Lock a thread from receiving replies.
     *
     * @return bool
     */
    public function lock()
    {
        return $this->update([
            'is_locked' => true
        ]);
    }

    /**
     * Unlock a thread from receiving replies.
     *
     * @return bool
     */
    public function unlock()
    {
        return $this->update([
            'is_locked' => false
        ]);
    }

    public function visits()
    {
        return new Visits($this);
    }

    public function markBestReply(Reply $reply)
    {
        $reply->thread->update([
            'best_reply_id' => $reply->id
        ]);
    }
}
