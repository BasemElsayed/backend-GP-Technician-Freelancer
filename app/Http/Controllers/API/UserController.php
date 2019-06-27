<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\Client; 
use App\Freelancer; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\DB;
use Image;

class UserController extends Controller 
{
    public $successStatus = 200;
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    
     public function login()
    { 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            $success['typeOfUsers'] =  $user->typeOfUsers; 
            $success['id'] =  $user->id; 
            $success['email'] =  $user->email;
            return response()->json(['success' => $success], $this-> successStatus); 
        } 

        $mail = User::where('email', request('email') )->get();
        if($mail->count())
        {
            return response()->json(['password'=> ['wrong password']], 401); 
        }
        else{ 
            return response()->json(['email'=> ['wrong mail']], 401); 
        } 
    }

    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    
     public function register(Request $request) 
    { 
        $chk = $request->get('typeOfUsers');
        if($chk == 1)
        {
            $validator = Validator::make($request->all(), [ 
                'name' => 'required',
                'address' => 'required', 
                'email' => 'required|email|unique:users', 
                'password' => 'required', 
                'c_password' => 'required|same:password',
                'mobileNumber' => 'required|min:11', 
            ]);
    
            if ($validator->fails()) 
            { 
                return response()->json(['error'=>$validator->errors()], 400);            
            }
            $originalUser = new User();
            $user = new Client();
            $user->name = $request->get('name');
            $user->password = bcrypt($request->get('password'));
            $user->email = $request->get('email');
            $user->mobileNumber = $request->get('mobileNumber');
            $user->address = $request->get('address');
            $user->typeOfUsers = $request->get('typeOfUsers');
            $user->xCordinate = $request->get('xCordinate');
            $user->yCordinate = $request->get('yCordinate');
            $originalUser->password = bcrypt($request->get('password'));
            $originalUser->email = $request->get('email');
            $originalUser->typeOfUsers = $request->get('typeOfUsers');
            $originalUser->save();
            $client = DB::table('users')->where('email', '=', $request->get('email'))->get();
            $user->id = $client[0]->id;
            $user->save();
            
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success'=>$success], $this-> successStatus); 
        }
        if($chk == 2)
        {
            $validator = Validator::make($request->all(), [ 
                'name' => 'required',
                'address' => 'required', 
                'email' => 'required|email|unique:users', 
                'password' => 'required', 
                'c_password' => 'required|same:password',
                'mobileNumber' => 'required|min:11', 
                'jobTitle' => 'required', 
            ]);
    
            if ($validator->fails()) 
            { 
                return response()->json(['error'=>$validator->errors()], 400);            
            }
            $user = new Freelancer();
            $originalUser = new User();
            $user->name = $request->get('name');
            $user->password = bcrypt($request->get('password'));
            $user->email = $request->get('email');
            $user->mobileNumber = $request->get('mobileNumber');
            $user->address = $request->get('address');
            $user->typeOfUsers = $request->get('typeOfUsers');
            $user->jobTitle = $request->get('jobTitle');
            $user->xCordinate = $request->get('xCordinate');
            $user->yCordinate = $request->get('yCordinate');
            $originalUser->password = bcrypt($request->get('password'));
            $originalUser->email = $request->get('email');
            $originalUser->typeOfUsers = $request->get('typeOfUsers');
            $originalUser->save();
            $freelancer = DB::table('users')->where('email', '=', $request->get('email'))->get();
            $user->id = $freelancer[0]->id;
            $user->save();
           

            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success'=>$success], $this-> successStatus); 
        }
        
    }





    public function edit(Request $request, $id)
    {       
        $chk = $request->get('typeOfUsers');
        if($chk == 1)
        {  
            $user = User::findOrFail($id);
            $client = Client::findOrFail($id);          
            $validator = Validator::make($request->all(), [ 
                'name' => 'required',
                'email' => 'required',
                'password' => 'required', 
                'c_password' => 'required|same:password',
                'mobileNumber' => 'min:11',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json($validator->errors(), 400);            
            }

            $users = User::where('email', $request->input('email') )->get();
            foreach($users as $currentUser)
            {
                if($currentUser->id != $id)
                {
                    return response()->json(['email'=> ['This email has been already token.']], 400);
                }
            }
            
            $input = $request->all();
            if(isset($input['password']))
                $input['password'] = bcrypt($input['password']); 
            
            $client->update($input);
            $input2 = ['email' => $input['email'], 'password' => $input['password']];
            $user->update($input2);

            return response()->json($user, $this-> successStatus); 
        }

        if($chk == 2)
        { 
            $user = User::findOrFail($id);
            $freelancer = Freelancer::findOrFail($id);           
            $validator = Validator::make($request->all(), [ 
                'name' => 'required',
                'email' => 'required',
                'password' => 'required', 
                'c_password' => 'same:password',
                'mobileNumber' => 'min:11',
            ]);

            if ($validator->fails()) 
            { 
                return response()->json($validator->errors(), 401);            
            }

            $users = User::where('email', $request->input('email') )->get();
            foreach($users as $currentUser)
            {
                if($currentUser->id != $id)
                {
                    return response()->json(['email'=> ['This email has been already token.']], 400);
                }
            }
            
            $input = $request->all();
            if(isset($input['password']))
                $input['password'] = bcrypt($input['password']); 
            
            $freelancer->update($input);
            $input2 = ['email' => $input['email'], 'password' => $input['password']];
            $user->update($input2);

            return response()->json($user, $this-> successStatus); 
        }
    }


    public function uploadPhoto(Request $request, $id)
    {
        $chk = $request->get('typeOfUsers');
        if($chk == 1)
        {  
            $client = Client::findOrFail($id);   
            if($request->hasFile('personalImage'))
            {
                $image = $request->file('personalImage');
                $name = str_slug($client->email) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/clientImages');
                $imagePath = $destinationPath . '/' . $name;
                $image = Image::make($image->getRealPath());
                $image->resize(600, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($imagePath);
                $input['personalImage'] = $name;
            }
            $client->update($input);
            return response()->json($client, $this-> successStatus); 
        }
        if($chk == 2)
        {  
            $freelancer = Freelancer::findOrFail($id);   
            if($request->hasFile('personalImage'))
            {
                $image = $request->file('personalImage');
                $name = str_slug($freelancer->email) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/freelancerImages');
                $imagePath = $destinationPath . '/' . $name;
                $image = Image::make($image->getRealPath());
                $image->resize(600, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($imagePath);
                $input['personalImage'] = $name;
            }
            $freelancer->update($input);
            return response()->json($freelancer, $this-> successStatus); 
        }
    }



    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */

    public function viewCurrentUser() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    }


    public function logoutAPI()
    {
        $user = Auth::user()->token();
        $user->revoke();
        return response()->json(['success' => "logout successfully"], $this-> successStatus); 
    }


}