<?php

namespace App\Http\Controllers;

use App\Models\admin;
use App\Models\loginModel;
use App\Models\Serviceinfo;
use App\Models\ShopVendor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Framework\Session\Session;

use function Pest\Laravel\json;

class LoginModelController extends Controller
{
    //function to check login credentials 
    public function login(Request $request)
    {

        //validate input requests coming from user

        $validate = Validator::make($request->all(), [
            'servicenumber' => 'required|size:6',
            'telephone' => 'required|digits:10'
        ]);

        if ($validate->fails()) {
            return response()->json(
                $validate->errors(),
                400
            );
        }

        $servicenumber = $request->servicenumber;
        $telephone = $request->telephone;

        //check if input requests exist in database
        $servicedata = Serviceinfo::where('servicenumber', $servicenumber)
            ->where('telephone', $telephone)
            ->first();
        //if true
        if ($servicedata) {
            $token = rand(1111, 9999);

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

                ], 200);
            } else {
                //if true create a new user with token
                User::create([
                    'servicenumber' => $request->servicenumber,
                    'token' => $token
                ]);

                //return response in json format
                return response()->json([
                    'message' => 'Token sent kindly enter',

                ], 200);
            }
        } else {
            //return response if false, no user found with current inputs
            return response()->json([
                'message' => 'No data was found, try again!',

            ],  401);
        }
    }

    //if credential are true, verify token to continue
    public function verify(Request $request)
    {

        $session = new Session();
        $session->start();

        //validate input request
        $validate = Validator::make($request->all(), [
            'token' => 'required|digits:4'
        ]);

        if ($validate->fails()) {
            return response()->json(
                $validate->errors(),
                400
            );
        }

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

            $session->isUserAuthenticated = true;
            $session->serviceNumber = $tokendata->id;


            //return response with access token 
            return response()->json([
                'accesstoken' => $accessToken,

            ], 200);
        }
        //if false
        else {
            return response()->json([
                'message' => 'invalid token provided',
            ], 401);
        }
    }

    //functions for vendor registration and login 

    public function registervendor(Request $request)
    {
        //validate input request
        $validate = Validator::make($request->all(), [
            'shopname' => 'required|string|min:10|unique:shopvendors,shopname',
            'email' => 'required|email|unique:shopvendors,email',
            'telephone' => 'required|unique:shopvendors,telephone|numeric|min:10',
            'location' => 'required|string',
            'region' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validate->fails()) {
            return response()->json(
                $validate->errors(),
                400
            );
        }

        //if validated, register vendor
        $vendordata = ShopVendor::create([
            'shopname' => $request->shopname,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'location' => $request->location,
            'region' => $request->region,
            'password' => Hash::make($request->password)
        ]);

        //if registration successfull
        if ($vendordata) {
            # code...

            return response()->json([
                'message' => 'Account has been registered and waiting for verification'
            ], 201);
        }
        //if false 
        else {

            return response()->json([
                'status' => 400,
                'message' => 'an error occured processing your request'
            ], 400);
        }
    }

    //function for vendor login
    public function vendorlogin(Request $request)
    {
        $session = new Session();

        $session->start();

        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        if ($validate->fails()) {
            return response()->json(
                $validate->errors(),
                400
            );
        }

        //chcek if email exists in db
        $vendordata = ShopVendor::where('email', $request->email)->first();

        //if false, return error
        if (!$vendordata) {
            return response()->json([
                'message' => 'invalid email or password',
            ], 401);
        }

        //check if password match, if false
        if (!Hash::check($request->password, $vendordata->password)) {
            # code...
            return response()->json([
                'message' => 'invalid password'
            ], 401);
        }

        //check if vendor has been approved by admin
        if ($vendordata->approved == 0) {
            # code...
            return response()->json([
                'message' => 'Account not yet approved, contact admin!',
            ], 401);
        }

        if ($vendordata->blockedstatus == 1) {
            # code...
            return response()->json([

                'message' => 'Account blocked, contact admin!',
            ], 401);
        }



        //revoke all token
        $vendordata->tokens()->delete();

        //generate token for vendor

        $accessToken = $vendordata->createToken($request->email)->plainTextToken;

        $session->isVendor = true;

        return response()->json([
            "message" => "Vendor logged in successfully",
            "access_token" => $accessToken
        ], 200);
    }

    //admin routes functions 
    public function adminlogin(Request $request)
    {
        //validate inputs from user
        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validate->fails()) {
            return response()->json(
                $validate->errors(),
                400
            );
        }

        //check if inputs are correct 
        $admindata = admin::where('email', $request->email)
            ->first();
        //if false, no match 
        if (!$admindata) {

            return response()->json(
                [

                    'message' => 'Invalid email or password provided'
                ],
                401
            );
        }

        //check if passwor match 
        if (!Hash::check($request->password, $admindata->password)) {
            //if false
            return response()->json(
                [

                    'message' => 'Invalid email or password provided'
                ],
                401
            );
        }

        $admindata->tokens()->delete();
        return  $admindata->createToken('admintoken')->plainTextToken;

        return response()->json([
            "message" => "Admin: $request->email logged in successfully"
        ], 200);
    }
}
