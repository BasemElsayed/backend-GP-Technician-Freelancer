<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requst extends Model
{
    //
    protected $fillable = [
        'client_id', 'freelancer_id', 'status', 'rate', 'freelancerRate',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
