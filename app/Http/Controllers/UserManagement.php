<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Session;

class UserManagement extends Controller
{
    public function register(Request $request)
	{
	    $user = new user;
	    $user->name = "random name";
	    $user->email = "random email";
	    $user->api_token = str_random(60);
        $user->save();   
	    
	    if(count($user) == 0){
	       $feedback = [
	           'status'     => "error",
	           'message'    => "register error"
	        ]; 
	       
	    }else{
	        $feedback = [
	           'status'     => "success",
	           'message'    => "register successfully",
	           'token'		=>  $user->api_token
	        ]; 
	    }
	    
	   return $feedback;
	}



	public function login(Request $request)
	{
		$login = user::where([
                    ['name', '=', $request->name],
                 ])->first();

        if($login == null){


	       $feedback = [
	           'status'     => "error",
	           'message'    => "auth error"
	        ]; 
	       
	    }else{

	    	$user = user::find($login->id);
	    	$user->api_token = str_random(60);
        	$user->save();
        	session(['token' => $user->api_token]);

	        $feedback = [
	           'status'     => "success",
	           'message'    => "login successfully",
	           'token'		=>  $user->api_token
	        ]; 

	        
	    }

	    return $feedback;
	}


	public function logout(Request $request)
	{
		$user = user::find($request->id);
	    $user->api_token = "";
        $user->save();

	    $feedback = [
           'status'     => "success",
           'message'    => "logout successfully"
        ]; 

        return $feedback;
	}

}
