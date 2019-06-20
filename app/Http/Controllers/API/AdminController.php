<?php

namespace App\Http\Controllers\API;

use App\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public $successStatus = 200;

    public function showAllFreelancersUsers()
    {
        $freelancers = DB::table('freelancers')->select('name', 'email', 'id', 'mobileNumber', 'jobTitle', 'allowedByAdmin', 'numberOfJobsDone')->get();
        $success['freelancers'] =  $freelancers;
        return response()->json($success, $this-> successStatus);
    }

    public function approveAccount($id)
    {
        DB::table('freelancers')->where('id', $id)->update(['allowedByAdmin' => 1]);
    }

    public function blockAccount($id)
    {
        DB::table('freelancers')->where('id', $id)->update(['allowedByAdmin' => 0]);
    }

    public function viewStatistics()
    {
        $services = DB::table('services')
            ->join('freelancers', 'services.name', '=', 'freelancers.jobTitle')
            ->select('services.name', 'services.serviceIcon', DB::raw('SUM(freelancers.numberOfJobsDone) as numberOfTotalJobsDone'))
            ->groupBy('services.name', 'services.serviceIcon')
            ->get();

        $success['services'] =  $services;
        return response()->json($success, $this-> successStatus);
    }
}
