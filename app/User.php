<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path'
    ];

    protected $appends = ['avatar'];

    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function visitedThreadCacheKey($thread)
    {
        return sprintf('users.%s.visits.%s', $this->id, $thread->id);
    }

    public function seen($thread)
    {
        cache()->forever(
            $this->visitedThreadCacheKey($thread), Carbon::now()
        );

        return $this;
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function avatar()
    {
        return $this->avatar_path ? '/storage/' . $this->avatar_path : '/storage/avatars/default.png';
    }

    public function getAvatarAttribute()
    {
        return $this->avatar();
    }

    public function isAdmin()
    {
        return in_array($this->name, ['Nasmed', 'Sof']);
    }
}
