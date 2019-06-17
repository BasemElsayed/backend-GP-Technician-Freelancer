<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Freelancer;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\DB;

class FreelancerController extends Controller
{
    public $successStatus = 200;
    
    public function viewAllRequests()
    {

    }

    public function viewRequest()
    {

    }

    public function showAllUsers()
    {

    }

    public function finishRequest()
    {

    }

    public function refuseRequest()
    {

    }

    public function acceptRequest()
    {

    }

    public function getFreelancerByEmail($email)
    {
        $user = DB::table('freelancers')->where('email', $email)->first();
        if($user)
        {        
            $success['freelancer'] =  $user;
            return response()->json($success, $this-> successStatus);
        }
        else
        {
            return response()->json(['email'=> ['wrong mail']], 401); 
        }
    }

}
