<?php

namespace App\Http\Controllers;

use App\Models\admin;
use App\Models\loginModel;
use App\Models\Serviceinfo;
use App\Models\ShopVendor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\json;

class LoginModelController extends Controller
{
    //function to check login credentials 
    public function login(Request $request)
    {
        //validate input requests coming from user
        $request->validate([
            'servicenumber' => 'required|size:6',
            'telephone' => 'required|digits:10'
        ]);

        $servicenumber = $request->servicenumber;
        $telephone = $request->telephone;

        //check if input requests exist in database
        $servicedata = Serviceinfo::where('servicenumber', $servicenumber)
                                  ->where('telephone', $telephone)
                                  ->first();
        //if true
        if($servicedata)
        {
            $token = rand(1111,9999);

            //check if service number already exists in users db
            $userdata = User::where('servicenumber', $servicenumber)->first();

            //if true update only token
            if ($userdata) {
                # code...
                $userdata->update([
                    'token' => $token
                ]);

                //send response in json format
                return response()->json([
                    'message' => 'Token sent kindly enter',
                    'status' => 200
                    ], 200);
            }
            else{
                //if true create a new user with token
                User::create([
                    'servicenumber' => $request->servicenumber,
                    'token' => $token
                ]);

                //return response in json format
                return response()->json([
                    'message' => 'Token sent kindly enter',
                    'status' => 200
                    ], 200);
            }
   
            
        }
        else
        {
            //return response if false, no user found with current inputs
            return response()->json([
                'message' => 'No data was found, try again!',
                'status' => 401
            ],  401);
        }

        
    }

    //if credential are true, verify token to continue
    public function verify(Request $request)
    {
        //validate input request
        $request->validate([
            'token' => 'required|digits:4'
        ]);

        //check if token exists 
        $tokendata = User::where('token', $request->token)
                        ->first();
        
        //if true
        if ($tokendata) {
            # code...
            //nullify token to prevent reuse 
            $tokendata->update([
                'token' => null
            ]);

            //revoke all token
            $tokendata->tokens()->delete();

            // Generate a new access token for the user
            $accessToken = $tokendata->createToken('User Login Token')->plainTextToken;

            //return response with access token 
            return response()->json([
                'status' => 200,
                'accesstoken' => $accessToken
            ], 200);
        }
        //if false
        else
        {
            return response()->json([
                'message' => 'invalid token provided',
                'status' => 401
            ], 401);
        }

    }

    //functions for vendor registration and login 

    public function registervendor(Request $request)
    {   
        //validate input request
        $request->validate([
            'shopname' => 'required|string|min:10|unique:shopvendors,shopname',
            'email' => 'required|email|unique:shopvendors,email',
            'telephone' => 'required|unique:shopvendors,telephone|numeric|min:10',
            'location' => 'required|string',
            'region' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        //if validated, register vendor
        $vendordata = ShopVendor::create([
            'shopname' => $request->shopname,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'location' => $request->location,
            'region' =>$request->region,
            'password' => Hash::make($request->password)
        ]);

        //if registration successfull
        if ($vendordata) {
            # code...
            
            return response()->json([
                'status' => 200,
                'message' => 'Account has been registered and waiting for verification'
            ], 200);

        }
        //if false 
        else
        {
            
        return response()->json([
            'status' => 400,
            'message' => 'an error occured processing your request'
        ], 400);
        }


    }

    //function for vendor login
    public function vendorlogin(Request $request)
    {
        //validate input requests from user
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|string|min:8'
            ]
            );
        //chcek if email exists in db
        $vendordata = ShopVendor::where('email', $request->email)->first();
        
        //if false, return error
        if(!$vendordata)
        {
            return response()->json([
                'status' => 401,
                'message' => 'invalid email or password',
            ], 401);
        }

        //check if password match, if false
        if (!Hash::check($request->password, $vendordata->password)) {
            # code...
            return response()->json([
                'status' => 401,
                'message' => 'invalid password'
            ]);
        }

        //check if vendor has been approved by admin
        if ($vendordata->approved == 1) {
            # code...
            return response()->json([
                'status' => 401,
                'message' => 'Account not yet approved, contact admin!',
            ], 401);
        }

        if ($vendordata->blockedstatus == 1) {
            # code...
            return response()->json([
                'status' => 401,
                'message' => 'Account blocked, contact admin!',
            ], 401);
        }



        //revoke all token
        $vendordata->tokens()->delete();

        //generate token for vendor
        return $vendordata->createToken($request->email)->plainTextToken;

        return response()->json([
            'status' => 200,
       ], 200);

    }

    //admin routes functions 
    public function adminlogin(Request $request)
    {
        //validate inputs from user
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        //check if inputs are correct 
        $admindata = admin::where('email', $request->email)
                            ->first();
        //if false, no match 
        if(!$admindata)
        {
            
            return response()->json(
                [
                    'status' => 401,
                    'message'=> 'Invalid email or password provided'
                ],
                401);
        }

        //check if passwor match 
        if(!Hash::check($request->password, $admindata->password))
        {
            //if false
            return response()->json(
                [
                    'status' => 401,
                    'message'=> 'Password is incorrect'
                ],
                401);
        }

        $admindata->tokens()->delete();
        return  $admindata->createToken('admintoken')->plainTextToken;

        //return if all true 
        return response()->json([
            'status' => 200,            
        ], 200);
       
    }
} 
