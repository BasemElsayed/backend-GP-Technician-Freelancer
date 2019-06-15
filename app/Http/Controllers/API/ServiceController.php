<?php

namespace App\Http\Controllers\API;

use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public $successStatus = 200;

   
    public function addService(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'description' => 'required', 
            'serviceIcon' => 'required'
        ]);
        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $service = new Service();
        if($request->hasFile('serviceIcon'))
        {
            $image = $request->file('serviceIcon');
            $name = str_slug($request->name) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/serviceIcons');
            $imagePath = $destinationPath . '/' . $name;
            $image->move($destinationPath, $name);
            $service->serviceIcon = $name;
        }
        $service->name = $request->get('name');
        $service->description = $request->get('description');
        $service->save();
        
        $success['service'] =  $service; 
        return response()->json(['success'=>$success], $this-> successStatus); 
    }

    public function editService()
    {

    }

    public function deleteService()
    {

    }

    public function viewService()
    {

    }

    public function viewAllService(Service $service)
    {
        $services = DB::table('services')->get();
        $success['services'] =  $services; 
        return response()->json($success, $this-> successStatus);
    }

}