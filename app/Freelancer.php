<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User; 
use Illuminate\Foundation\Auth\Freelancer as Authenticatable;

class Freelancer extends User
{
    //
    protected $fillable = [
        'name', 'email', 'password', 'mobileNumber', 'typeOfUsers', 'personalImage', 'address', 'numberOfJobsDone', 'numberOfCurrentRequests', 'xCordinate', 'yCordinate', 'jobTitle', 'allowedByAdmin', 'limitNumberOfWorks', 'allowedToRequest', 'totalRate',
    ];

    

    protected $hidden = [
        'password', 'remember_token',
    ];
}
