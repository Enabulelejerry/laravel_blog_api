<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Hash;
use Auth;
use Illuminate\Http\Request;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class AuthController extends Controller
{
    public function Register(Request $request){
        
     try {
        
        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3'
        
         ]);

         if($validator->fails()){
             return response()->json(
              ['errors' => $validator->errors()]
             );
         }
         $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>Hash::make($request->password)
         ]);

         $token = $user->createToken('app')->accessToken;

         return response([
            'message' => "Successfully Registered",
            'token' => $token,
            'user' => $user
         ],200);

     } catch (Exception $ex) {
        return response([
            'messsage' => $ex->getMessage()
          ],401);
     }
    }

    //Auth Login api

    public function Login(Request $request){
         
        $validator =  Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required'
        
         ]);

         if($validator->fails()){
             return response()->json(
              ['errors' => $validator->errors()]
             );
         }

         if(Auth::attempt($request->only('email','password'))){
            $user = Auth::user();
            $token = $user->createToken('app')->accessToken;

            return response([
              'message' => "Successfully Login",
              'token' => $token,
              'user' => $user,

            ],200);
         }else{
            return response([
                'error' => 'invalid email or Password'
            ],401);
         }
    }

    //update user

    public function updateUser(Request $request){
        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
         ]);

         if($validator->fails()){
             return response()->json(
              ['errors' => $validator->errors()]
             );
         }
        
   $user_id = auth()->user()->id;
      $user = User::find($user_id);
      $user->name  = $request->name;
      $user->email = $request->email;
      if($request->password){
        $user->password = Hash::make($request->password);
      }
      $user->update();
      if($user){
        return response([
            'message' => "User Updated Succcessfully",
            'user' => $user
          ],200);
      }else{
         return response([
            'error' => "Something happpend",
          ],401);
      }
     
    }

    public function deleteUser(){
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $user->delete();
        return response([
            'message' => "User deleted Succcessfully",
          ],200);
    }

}
