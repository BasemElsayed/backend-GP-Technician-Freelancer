<?php

namespace App\Http\Controllers\API;

use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\DB;
use Image;

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
            $name = str_slug($request->id) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/serviceIcons');
            $imagePath = $destinationPath . '/' . $name;
            $image = Image::make($image->getRealPath());
            $image->resize(223, 203, function ($constraint) {
                $constraint->aspectRatio();
            })->save($imagePath);
            $service->serviceIcon = $name;
        }
        else
        {
            return response()->json(['serviceIcon'=>"Service Icon is Required"], 401);
        }
        $service->name = $request->get('name');
        $service->description = $request->get('description');
        $service->save();
        
        $success['service'] =  $service; 
        return response()->json(['success'=>$success], $this-> successStatus); 
    }

    public function editService(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'description' => 'required', 
        ]);
        if ($validator->fails()) 
        { 
            return response()->json($validator->errors(), 401);            
        }
        $input = $request->all();
        $service->update($input);
        return response()->json($service, $this-> successStatus);
    }

    public function updateServiceIcon(Request $request, $id)
    {
        $service = Service::findOrFail($id);   
        if($request->hasFile('serviceIcon'))
        {
            $image = $request->file('serviceIcon');
            $name = str_slug($service->id) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/serviceIcons');
            $imagePath = $destinationPath . '/' . $name;
            $image = Image::make($image->getRealPath());
            $image->resize(223, 203, function ($constraint) {
                $constraint->aspectRatio();
            })->save($imagePath);
            $input['serviceIcon'] = $name;
        }
        $service->update($input);
        return response()->json($service, $this-> successStatus); 
    }
    

    public function deleteService($id)
    {
        DB::table('services')->where('id', '=', $id)->delete();
    }

    public function viewService($id)
    {
        $service = DB::table('services')->where('id', $id)->get();
        $success['service'] =  $service; 
        return response()->json($success, $this-> successStatus);
    }

    public function viewAllService(Service $service)
    {
        $services = DB::table('services')->get();
        $success['services'] =  $services; 
        return response()->json($success, $this-> successStatus);
    }

}