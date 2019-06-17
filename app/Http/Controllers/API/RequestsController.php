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
            ->select('requsts.status', 'freelancers.email', 'freelancers.name', 'freelancers.mobileNumber', 'requsts.id', 'freelancers.jobTitle', 'services.serviceIcon', 'requsts.rate', 'requsts.freelancerRate', 'requsts.freelancer_id', 'requsts.client_id')
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
            ->select('requsts.status', 'freelancers.email', 'freelancers.name', 'freelancers.mobileNumber', 'requsts.id', 'freelancers.jobTitle', 'services.serviceIcon', 'requsts.rate', 'requsts.freelancerRate', 'requsts.freelancer_id', 'requsts.client_id')
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
            ->select('requsts.status', 'freelancers.email', 'freelancers.name', 'freelancers.mobileNumber', 'requsts.id', 'freelancers.jobTitle', 'services.serviceIcon', 'requsts.rate', 'requsts.freelancerRate', 'requsts.freelancer_id', 'requsts.client_id')
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
            ->select('requsts.status', 'freelancers.email', 'freelancers.name', 'freelancers.mobileNumber', 'requsts.id', 'freelancers.jobTitle', 'services.serviceIcon', 'requsts.rate', 'requsts.freelancerRate', 'requsts.freelancer_id', 'requsts.client_id')
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
        return $requsts;
    }



    public function updateStatus(Request $request, $id)
    {
        $reqst = Requst::findOrFail($id);  
        $input = $request->all();
        $reqst->update($input);
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
            ->select('requsts.status', 'clients.email', 'clients.name', 'clients.mobileNumber', 'requsts.id', 'requsts.freelancer_id', 'requsts.client_id', 'clients.xCordinate','clients.yCordinate', 'clients.address')
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
            ->select('requsts.status', 'clients.email', 'clients.name', 'clients.mobileNumber', 'requsts.id', 'requsts.freelancer_id', 'requsts.client_id', 'clients.xCordinate','clients.yCordinate', 'clients.address')
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
            ->select('requsts.status', 'clients.email', 'clients.name', 'clients.mobileNumber', 'requsts.id', 'requsts.freelancer_id', 'requsts.client_id', 'clients.xCordinate','clients.yCordinate', 'clients.address')
            ->get();

        $success['requsts'] =  $requsts;
        return response()->json($success, $this-> successStatus);
    }

    


}
