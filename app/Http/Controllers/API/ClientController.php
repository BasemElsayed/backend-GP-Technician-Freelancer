<?php

namespace App\Http\Controllers\API;

use App\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public $successStatus = 200;

    public function showWorkersByGategory($category)
    {
        $freelancers = DB::table('freelancers')->where([
            ['jobTitle', '=', $category],
            ['allowedByAdmin', '=', '1'],
        ])->select('name', 'email', 'mobileNumber', 'personalImage', 'numberOfJobsDone', 'jobTitle', 'id', 'address', 'totalRate')->get();

        $success['freelancers'] =  $freelancers; 
        return response()->json($success, $this-> successStatus);
    }

    public function showWorkersByGPS($latitude, $longitude, $category)
    {
        $radius = 10;
        $haversine = "(6371 * acos(cos(radians($latitude)) * cos(radians(xCordinate)) * cos(radians(yCordinate) - radians($longitude)) + sin(radians($latitude)) * sin(radians(xCordinate))))";

        $freelancers = DB::table('freelancers')->where([
            ['jobTitle', '=', $category],
            ['allowedByAdmin', '=', '1'],
        ])->select('name', 'email', 'mobileNumber', 'personalImage', 'numberOfJobsDone', 'jobTitle', 'id', 'address', 'totalRate')
            ->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} < ?", [$radius])->get();


        $success['freelancers'] =  $freelancers; 
        return response()->json($success, $this-> successStatus);
    }
    
    public function selectWorker()
    {

    }

    public function getClientByEmail($email)
    {
        $user = DB::table('clients')->where('email', $email)->first();
        if($user)
        {        
            $success['client'] =  $user;
            return response()->json($success, $this-> successStatus);
        }
        else
        {
            return response()->json(['email'=> ['wrong mail']], 401); 
        }
    }

}
