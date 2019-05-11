<?php

namespace App;

trait Favoriteable
{

    protected static function bootFavoriteable()
    {
        self::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

    /**
     * Check if the authenticated user favorite the reply.
     *
     * @return bool
     */
    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', \Auth::id())->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function favorite()
    {
        $attributes = ['user_id' => \Auth::id()];

        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    public function unfavorite()
    {
        $attributes = ['user_id' => \Auth::id()];

        return $this->favorites()->where($attributes)->get()->each->delete();
    }
}
