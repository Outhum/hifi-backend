<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ChangePasswordRequest;
use App\User;
use DB;

class ChangePasswordController extends Controller
{
    public function updatepassword(ChangePasswordRequest $request){
       //return  $this->getUserDetails($request); 
      return $this->getUserDetails($request)->count()> 0? $this->changePassword($request): $this->tokenNotFound();
   
    } 
    public function getUserDetails($request){
      return DB::table('password_reset')->where(['email'=>$request->email,'token'=>$request->resetToken]);
    }  
    public function changePassword($request){
        $user = User::whereEmail($request->email)->first();
        if($user){
            $user->update(['password'=>$request->password]);
            $this->getUserDetails($request)->delete();
            return response()->json(['data'=>'Password updated successfully'],Response::HTTP_OK);
        }
        
    }
    public function tokenNotFound(){
       
        return response()->json(['error'=>'This link is expired please reset your password again!'],Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
