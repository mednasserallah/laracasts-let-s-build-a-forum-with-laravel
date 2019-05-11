<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'type',
        'user_id',
        'created_at'
    ];

    public function subject()
    {
        return $this->morphTo();
    }

    public static function feed(User $user, $limit = 50)
    {
        $user = $user ?? auth()->user();

        return $user->activities()
            ->latest()
            ->with('subject')
            ->take($limit)
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('Y-m-d');
            });
    }
}
