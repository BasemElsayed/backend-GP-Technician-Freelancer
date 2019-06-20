<?php

namespace App\Http\Controllers\API;

use App\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\DB;

class RegionsController extends Controller
{
    
    public $successStatus = 200;

    public function addRegion(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
        ]);
        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $region = new Region();
        $region->name = $request->get('name');
        $region->save();
        $success['region'] =  $region; 
        return response()->json(['success'=>$success], $this-> successStatus); 
    }
    public function viewAllRegions(Region $region)
    {
        $regions = DB::table('regions')->get();
        $success['regions'] =  $regions; 
        return response()->json($success, $this-> successStatus);
    }
    
    public function deleteRegion($id)
    {
        DB::table('regions')->where('id', '=', $id)->delete();
    }

}
