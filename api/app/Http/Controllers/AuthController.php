<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // return $request;
    	//Validate data
        $data = $request->only('name', 'email', 'password' , 'confirm_password');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50',
            'confirm_password'=>'required_with:password|same:password',
        ]);


        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new user
        $user = User::create([
        	'name' => $request->name,
        	'email' => $request->email,
        	'password' => bcrypt($request->password)
        ]);
        $user->assignRole($request->roles);
        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
    	    return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }
        
 		// Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'role' => Auth::user()->roles->pluck('name')->all(),
            'user_has_permissions' => Auth::user()->getAllPermissions()->pluck('name')
        ]);
    }

    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

		//Request is validated, do logout
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get_user(Request $request)
    {
        // $this->validate($request, [
        //     'token' => 'required'
        // ]);

        // $user = JWTAuth::authenticate($request->token);

        $user = JWTAuth::authenticate();
        $user['has_permissions'] = $user->getAllPermissions()->pluck('name');

        return response()->json([
            'user' => $user,
        ]);
    }

    public function test(){
        return "Working Well";
    }

    public function users(){
        $users = User::with('roles')->get();
        return response()->json(['users' => $users]);
    }

    public function showUser($id){
        $user = User::find($id);
        $userRole = $user->roles->pluck('id','name')->all();
        return response()->json(['user' => $user, 'userRole' => $userRole, ]);
    }

    public function updateUser(Request $request){

        $data = $request->only('name', 'email', 'password' , 'confirm_password');

        // $validator = Validator::make($data, [
        //     'name' => 'required|string',
        //     'email' => 'required|email',
        //     // 'password' => 'required|string|min:6|max:50',
        //     // 'confirm_password'=>'required_with:password|same:password'
        // ]);

        //Send failed response if request is not valid
        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->messages()], 200);
        // }

        //Request is valid, create new user
        $user = User::findOrFail($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->update();

        $user->syncRoles($request->roles);
        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }
}
