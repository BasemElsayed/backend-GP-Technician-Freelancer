<?php

namespace App\Http\Controllers\API;

use App\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{

    public $successStatus = 200;
   
    public function review(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'description' => 'required'
        ]);
        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $comment = new Comment();
        $comment->client_id = $request->get('client_id');
        $comment->freelancer_id = $request->get('freelancer_id');
        $comment->description = $request->get('description');
        $comment->typeOfUsers = $request->get('typeOfUsers');
        $comment->save();
        
        $success['comment'] =  $comment; 
        return response()->json(['success'=>$success], $this-> successStatus); 
    }

    public function editComment()
    {

    }

    public function deleteComment()
    {
        
    }

    public function showAllClientComments($email)
    {
        $comments = DB::table('comments')
            ->join('clients', 'clients.id', '=', 'comments.client_id')
            ->join('freelancers', 'freelancers.id', '=', 'comments.freelancer_id')
            ->where([
                ['clients.email', '=', $email],
                ['comments.typeOfUsers', '=', '1'],
            ])
            ->select('comments.description', 'clients.name', 'freelancers.name', 'freelancers.email')
            ->get();
        $success['comments'] =  $comments; 
        return response()->json($success, $this-> successStatus);
    }

    public function showAllFreelancerComments($email)
    {
        $comments = DB::table('comments')
            ->join('clients', 'clients.id', '=', 'comments.client_id')
            ->join('freelancers', 'freelancers.id', '=', 'comments.freelancer_id')
            ->where([
                ['freelancers.email', '=', $email],
                ['comments.typeOfUsers', '=', '2'],
            ])
            ->select('comments.description', 'clients.name', 'freelancers.name', 'freelancers.email')
            ->get();
        $success['comments'] =  $comments; 
        return response()->json($success, $this-> successStatus);
    }

}
