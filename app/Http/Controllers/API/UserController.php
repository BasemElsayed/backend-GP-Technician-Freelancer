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
                return response()->json(['error'=>$validator->errors()], 401);            
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
            $user->save();
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success'=>$success], $this-> successStatus); 
        }
        if($chk == 2)
        {
            $validator = Validator::make($request->all(), [ 
                'name' => 'required',
                'address' => 'required', 
                'email' => 'required|email|unique:clients|unique:freelancers', 
                'password' => 'required', 
                'c_password' => 'required|same:password',
                'mobileNumber' => 'required|min:11', 
                'jobTitle' => 'required', 
            ]);
    
            if ($validator->fails()) 
            { 
                return response()->json(['error'=>$validator->errors()], 401);            
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
            $user->save();

            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success'=>$success], $this-> successStatus); 
        }
        
        /*if($request->hasFile('personalImage'))
        {
            $image = $request->file('personalImage');
            $name = str_slug($request->email) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/usersImages');
            $imagePath = $destinationPath . '/' . $name;
            $image->move($destinationPath, $name);
            $user->personalImage = $name;
        }*/
        
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

    public function edit()
    {

    } 


    public function addressByGPS()
    {
        
    }

    public function logout()
    {
        
    }


}