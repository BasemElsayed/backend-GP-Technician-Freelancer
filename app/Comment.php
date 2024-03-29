<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $fillable = [
        'client_id', 'description', 'freelancer_id', 'typeOfUsers',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}