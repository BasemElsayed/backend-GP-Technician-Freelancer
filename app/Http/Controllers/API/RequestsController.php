<?php

namespace App\Http\Controllers\API;

use App\Requst;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\DB;

class RequestsController extends Controller
{
    public $successStatus = 200;

    public function requestWorker(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'client_id' => 'required',
            'freelancer_id' => 'required',
            'status' => 'required', 
        ]);

        $requsts = DB::table('requsts')->where([
            ['client_id', '=', $request->get('client_id')],
            ['freelancer_id', '=', $request->get('freelancer_id')],
            ['status', '=', 3],
        ])->first();
        
        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        if ($requsts) 
        { 
            return response()->json(['error'=>"You have already request this worker."], 401);            
        }

        $req = new Requst();
        $req->client_id = $request->get('client_id');
        $req->freelancer_id = $request->get('freelancer_id');
        $req->status = $request->get('status');
        $req->save();

        $user = DB::table('clients')->where([
            ['id', '=', $request->get('client_id')],
        ])->first();
        $newNumberOfRequests = $user->numberOfCurrentRequests + 1;
        DB::table('clients')->where('id', $request->get('client_id'))->update(['numberOfCurrentRequests' => $newNumberOfRequests]);

        $freelancer = DB::table('freelancers')->where([
            ['id', '=', $request->get('freelancer_id')],
        ])->first();
        $newNumberOfRequests = $freelancer->numberOfCurrentRequests + 1;
        DB::table('freelancers')->where('id', $request->get('freelancer_id'))->update(['numberOfCurrentRequests' => $newNumberOfRequests]);

        $success['request'] =  $req;
        return response()->json(['success'=>$success], $this-> successStatus); 
    }

    public function cancelRequest($id)
    {
        DB::table('requsts')->where('id', '=', $id)->delete();
    }

    
    public function showFinishedRequests($id)
    {        
        $requsts = DB::table('requsts')
            ->join('freelancers', 'freelancers.id', '=', 'requsts.freelancer_id')
            ->join('services', 'services.name', '=', 'freelancers.jobTitle')
            ->where([
                ['client_id', '=', $id],
                ['status', '=', '2'],
            ])
            ->select('requsts.status', 'freelancers.email', 'freelancers.name', 'freelancers.mobileNumber', 'requsts.id', 'freelancers.jobTitle', 'services.serviceIcon', 'requsts.rate', 'requsts.freelancerRate', 'requsts.freelancer_id', 'requsts.client_id', 'freelancers.totalRate', 'freelancers.address')
            ->get();

        $success['requsts'] =  $requsts;
        return response()->json($success, $this-> successStatus);
    }

    
    public function showUnfinishedRequests($id)
    {
        $requsts = DB::table('requsts')
            ->join('freelancers', 'freelancers.id', '=', 'requsts.freelancer_id')
            ->join('services', 'services.name', '=', 'freelancers.jobTitle')
            ->where([
                ['client_id', '=', $id],
                ['status', '=', '1'],
            ])
            ->select('requsts.status', 'freelancers.email', 'freelancers.name', 'freelancers.mobileNumber', 'requsts.id', 'freelancers.jobTitle', 'services.serviceIcon', 'requsts.rate', 'requsts.freelancerRate', 'requsts.freelancer_id', 'requsts.client_id', 'freelancers.totalRate', 'freelancers.address')
            ->get();

        $success['requsts'] =  $requsts;
        return response()->json($success, $this-> successStatus);
    }

    
    
    public function showCancelledRequests($id)
    {        
        $requsts = DB::table('requsts')
            ->join('freelancers', 'freelancers.id', '=', 'requsts.freelancer_id')
            ->join('services', 'services.name', '=', 'freelancers.jobTitle')
            ->where([
                ['client_id', '=', $id],
                ['status', '=', '0'],
            ])
            ->select('requsts.status', 'freelancers.email', 'freelancers.name', 'freelancers.mobileNumber', 'requsts.id', 'freelancers.jobTitle', 'services.serviceIcon', 'requsts.rate', 'requsts.freelancerRate', 'requsts.freelancer_id', 'requsts.client_id', 'freelancers.totalRate', 'freelancers.address')
            ->get();

        $success['requsts'] =  $requsts;
        return response()->json($success, $this-> successStatus);
    }

    public function showWaitingRequests($id)
    {        
        $requsts = DB::table('requsts')
            ->join('freelancers', 'freelancers.id', '=', 'requsts.freelancer_id')
            ->join('services', 'services.name', '=', 'freelancers.jobTitle')
            ->where([
                ['client_id', '=', $id],
                ['status', '=', '3'],
            ])
            ->select('requsts.status', 'freelancers.email', 'freelancers.name', 'freelancers.mobileNumber', 'requsts.id', 'freelancers.jobTitle', 'services.serviceIcon', 'requsts.rate', 'requsts.freelancerRate', 'requsts.freelancer_id', 'requsts.client_id', 'freelancers.totalRate', 'freelancers.address')
            ->get();

        $success['requsts'] =  $requsts;
        return response()->json($success, $this-> successStatus);
    }


    public function updateRate($id, $rate)
    {
        DB::table('requsts')->where('id', $id)->update(['rate' => $rate]);
        $requsts = DB::table('requsts')->where('id', $id)->get();

        $clientRequsts = DB::table('requsts')->where([
            ['client_id', '=', $requsts[0]->client_id],
            ['status', '=', '2'],
        ])->get();
    
        $chkAll = true;
        foreach($clientRequsts as $element)
        {
            if($element->rate == 0)
            {
                $chkAll = false;
            }
        }
        if($chkAll === true)
        {
            DB::table('clients')->where('id', $requsts[0]->client_id)->update(['allowedToRequest' => 1]);
        }

        $freelancerRates = DB::table('requsts')->where([
            ['freelancer_id', '=', $requsts[0]->freelancer_id],
            ['status', '=', '2'],
            ['freelancerRate', '<>', '0'],
        ])->get();
        $count = 0;
        foreach($freelancerRates as $element)
        {
            $count = $count + 1;
        }

        $freelanc = DB::table('freelancers')->where('id', $requsts[0]->freelancer_id)->get();
        $newRate = ($rate + (($count-1)*$freelanc[0]->totalRate)) / $count;
        DB::table('freelancers')->where('id', $requsts[0]->freelancer_id)->update(['totalRate' => $newRate]);
        return $requsts;
    }


    public function updateRateFreelancer($id, $rate)
    {
        DB::table('requsts')->where('id', $id)->update(['freelancerRate' => $rate]);
        $requsts = DB::table('requsts')->where('id', $id)->get();

        $freelancerRequsts = DB::table('requsts')->where([
            ['freelancer_id', '=', $requsts[0]->freelancer_id],
            ['status', '=', '2'],
        ])->get();

        $chkAll = true;
        foreach($freelancerRequsts as $element)
        {
            if($element->freelancerRate == 0)
            {
                $chkAll = false;
            }
        }
        if($chkAll === true)
        {
            DB::table('freelancers')->where('id', $requsts[0]->freelancer_id)->update(['allowedToRequest' => 1]);
        }

        $clientRates = DB::table('requsts')->where([
            ['client_id', '=', $requsts[0]->client_id],
            ['status', '=', '2'],
            ['freelancerRate', '<>', '0'],
        ])->get();
        $count = 0;
        foreach($clientRates as $element)
        {
            $count = $count + 1;
        }

        $client = DB::table('clients')->where('id', $requsts[0]->client_id)->get();
        $newRate = ($rate + (($count-1)*$client[0]->totalRate)) / $count;
        DB::table('clients')->where('id', $requsts[0]->client_id)->update(['totalRate' => $newRate]);

        return $requsts;
    }



    public function updateStatus(Request $request, $id)
    {
        $reqst = Requst::findOrFail($id);  
        $input = $request->all();
        $reqst->update($input);
        if($request->get('status') == 1)
        {
            $freelancer = DB::table('freelancers')->where('id', $reqst->freelancer_id)->get();
            $limitNumberOfWorks = $freelancer[0]->limitNumberOfWorks - 1;
            DB::table('freelancers')->where('id', $freelancer[0]->id)->update(['limitNumberOfWorks' => $limitNumberOfWorks]);
        }
        if($request->get('status') == 2)
        {
            $freelancer = DB::table('freelancers')->where('id', $reqst->freelancer_id)->get();
            $numberofjob = $freelancer[0]->numberOfJobsDone;
            $numberofjob = $numberofjob + 1;
            DB::table('freelancers')->where('id', $freelancer[0]->id)->update(['allowedToRequest' => 0]);
            DB::table('freelancers')->where('id', $freelancer[0]->id)->update(['numberOfJobsDone' => $numberofjob]);

            $client = DB::table('clients')->where('id', $reqst->client_id)->get();
            $numberofjobd = $client[0]->numberOfJobsDone;
            $numberofjobd = $numberofjobd + 1;
            DB::table('clients')->where('id', $client[0]->id)->update(['numberOfJobsDone' => $numberofjobd]);
        }

        return response()->json($reqst, $this-> successStatus); 
    }




    public function showWaitingRequestsFreelancer($id)
    {        
        $requsts = DB::table('requsts')
            ->join('clients', 'clients.id', '=', 'requsts.client_id')
            ->where([
                ['freelancer_id', '=', $id],
                ['status', '=', '3'],
            ])
            ->select('requsts.status', 'clients.email', 'clients.name', 'clients.mobileNumber', 'requsts.id', 'requsts.freelancer_id', 'requsts.client_id', 'clients.xCordinate','clients.yCordinate', 'clients.address', 'requsts.freelancerRate', 'clients.totalRate')
            ->get();

        $success['requsts'] =  $requsts;
        return response()->json($success, $this-> successStatus);
    }

    public function showAcceptingRequestsFreelancer($id)
    {        
        $requsts = DB::table('requsts')
            ->join('clients', 'clients.id', '=', 'requsts.client_id')
            ->where([
                ['freelancer_id', '=', $id],
                ['status', '=', '1'],
            ])
            ->select('requsts.status', 'clients.email', 'clients.name', 'clients.mobileNumber', 'requsts.id', 'requsts.freelancer_id', 'requsts.client_id', 'clients.xCordinate','clients.yCordinate', 'clients.address', 'requsts.freelancerRate', 'clients.totalRate')
            ->get();

        $success['requsts'] =  $requsts;
        return response()->json($success, $this-> successStatus);
    }

    public function showFinishedRequestsFreelancer($id)
    {        
        $requsts = DB::table('requsts')
            ->join('clients', 'clients.id', '=', 'requsts.client_id')
            ->where([
                ['freelancer_id', '=', $id],
                ['status', '=', '2'],
            ])
            ->select('requsts.status', 'clients.email', 'clients.name', 'clients.mobileNumber', 'requsts.id', 'requsts.freelancer_id', 'requsts.client_id', 'clients.xCordinate','clients.yCordinate', 'clients.address', 'requsts.freelancerRate', 'clients.totalRate')
            ->get();

        $success['requsts'] =  $requsts;
        return response()->json($success, $this-> successStatus);
    }

    public function showFinishedRequestsNeedsRate($id)
    {        
        $requsts = DB::table('requsts')
            ->join('clients', 'clients.id', '=', 'requsts.client_id')
            ->where([
                ['freelancer_id', '=', $id],
                ['status', '=', '2'],
                ['freelancerRate', '=', '0'],
            ])
            ->select('requsts.status', 'clients.email', 'clients.name', 'clients.mobileNumber', 'requsts.id', 'requsts.freelancer_id', 'requsts.client_id', 'clients.xCordinate','clients.yCordinate', 'clients.address', 'requsts.freelancerRate', 'clients.totalRate')
            ->get();
            
        $success['requsts'] =  $requsts;
        return response()->json($success, $this-> successStatus);
    }

    

}
