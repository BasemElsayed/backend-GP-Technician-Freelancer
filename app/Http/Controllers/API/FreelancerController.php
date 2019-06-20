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

    public function getFreelancerByID($id)
    {
        $user = DB::table('freelancers')->where('id', $id)->first();
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
