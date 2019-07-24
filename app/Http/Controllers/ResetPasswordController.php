<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Mail\ResetPasswordMail;
use DB;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function  sendmail(Request $request){
      //return $request->all();
      
      if(!$this->validateEmail($request->email)){
          return $this->failedResponse();
        
      }
      $this->send($request->email);
      return $this->successResponse();
    }
    public function validateEmail($email){
      //dd($email);
      return !! User::where('email',$email)->first(); //double esclametory marks for returning boolean
      //print_r($user);die();
    }
    public function failedResponse(){
      return response()->json([
        'error' => 'Email does\'t found on the database'
      ],Response::HTTP_NOT_FOUND);
    }
    public function successResponse(){
      return response()->json([
        'data' => 'Mail send successfully! Please check your inbox.'
      ],Response::HTTP_OK);
    }

    public function send($email){
      
      $token =  $this->createtoken($email);
      Mail::to($email)->send(new ResetPasswordMail($token));
    }
    public function createtoken($email){
      $oldtoken = DB::table('password_reset')->where('email',$email)->first();
      if($oldtoken){
        return $oldtoken;
      }
      $token = str_random(60);
      $this->savetoken($token,$email);
      return $token;
    }
    public function savetoken($token,$email){
      Db::table('password_reset')->insert([
        'email'      => $email,
        'token'      => $token,
        'created_at' => Carbon::now(),
      ]);
    }
}    
