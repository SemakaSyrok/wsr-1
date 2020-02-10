<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $guarded = [

    ];

    public function users()
    {
        return $this->belongsToMany(\App\User::class, 'photo_user', 'photo_id', 'user_id');
    }
}
