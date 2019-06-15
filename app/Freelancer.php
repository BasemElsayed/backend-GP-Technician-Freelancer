<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User; 
use Illuminate\Foundation\Auth\Freelancer as Authenticatable;

class Freelancer extends User
{
    //
    protected $fillable = [
        'jobTitle', 'allowedByAdmin', 'limitNumberOfWorks', 
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
}
