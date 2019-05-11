<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use RecordActivity;

    protected $fillable = ['user_id'];

    public function favorited()
    {
        return $this->morphTo('favoriteable');
    }
}
