<?php

namespace App\Http\Controllers\API;

use App\Portfolio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\DB;
use Image;

class PortfolioController extends Controller
{
    

    public $successStatus = 200;

   
    public function updatePortfolio(Request $request)
    {
        $count;
        $freelancerPortfolio = DB::table('portfolios')->where('freelancer_id', $request->freelancer_id)->get();
        if(sizeof($freelancerPortfolio) == 0)
        {
            global $count;
            $count = 1;
        }
        else
        {
            global  $count;
            $count = Portfolio::max('id') + 1;
        }

        $portfolio = new Portfolio();
        if($request->hasFile('portfolioImage'))
        {
            $image = $request->file('portfolioImage');
            $name = str_slug($count) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/freelancerPortfolios');
            $imagePath = $destinationPath . '/' . $name;
            $image = Image::make($image->getRealPath());
            $image->resize(600, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($imagePath);
            $portfolio->portfolioImage = $name;
        }
        $portfolio->freelancer_id = $request->get('freelancer_id');
        $portfolio->save();
        
        $success['portfolio'] =  $portfolio; 
        return response()->json(['success'=>$success], $this-> successStatus);
    }

    public function viewAllPortfolio($freelancer_id)
    {
        $freelancerPortfolio = DB::table('portfolios')->where('freelancer_id', $freelancer_id)->get();
        $success['freelancerPortfolio'] =  $freelancerPortfolio; 
        return response()->json($success, $this->successStatus);
    }

    public function delete($id)
    {
        DB::table('portfolios')->where('id', '=', $id)->delete();
    }

}
