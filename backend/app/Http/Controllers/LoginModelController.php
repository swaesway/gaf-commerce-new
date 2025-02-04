<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\admin;
use Twilio\Rest\Client;
use App\Models\Wishlist;
use App\Models\loginModel;
use App\Models\ShopVendor;
use App\Models\Serviceinfo;
use Illuminate\Http\Request;
use Framework\Session\Session;
use App\Models\ProofOfBusiness;

use function Pest\Laravel\json;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

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

        $sid = env("TWILIO_SID");
        $twilio_token = env('TWILIO_AUTH_TOKEN');

        $client = new Client($sid, $twilio_token);

        if ($validate->fails()) {
            return response()->json(
                $validate->errors(),
                400
            );
        }

        $servicenumber = $request->servicenumber;
        $telephone = $request->telephone;

        $serializeTele = explode("0", $request->telephone, 2);

        //check if input requests exist in database
        $servicedata = Serviceinfo::where('servicenumber', $servicenumber)
            ->where('telephone', $telephone)
            ->first();
        //if true
        if ($servicedata) {
            $token = rand(110210, 999999);

            //check if service number already exists in users db
            $userdata = User::where('servicenumber', $servicenumber)->first();

            //if true update only token
            if ($userdata) {
                # code...
                $client->messages->create(
                    "+233" . $serializeTele[1],
                    [
                        'from' => '+15594713650',
                        'body' => $token
                    ]
                );

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
                'message' => 'Invalid Credentials',

            ],  401);
        }
    }

    //if credential are true, verify token to continue
    public function verify(Request $request)
    {

        // $session = new Session();
        // $session->start();

        //validate input request
        $validate = Validator::make($request->all(), [
            'token' => 'required|digits:6'
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

            // $session->isUserAuthenticated = true;
            // $session->serviceNumber = $tokendata->id;

            // $wishList = (array) $session->wishList;

            // if ($wishList && count($wishList) > 0) {
            //     foreach ($wishList as $wishlist) {
            //         $foundWishList = Wishlist::where("servicenumber", $session->serviceNumber)
            //             ->where("product_id", $wishlist["product_id"])
            //             ->first();

            //         if (!$foundWishList) {
            //             $wishlist["servicenumber"] = $session->serviceNumber;
            //             Wishlist::create($wishlist);
            //         }
            //     }

            //     $session->remove("wishList");
            // }

            //return response with access token 
            return response()->json([
                "message" => "Authenticated Successfully",
                'accessToken' => $accessToken,

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
            'shopname' => 'required|string|unique:shopvendors,shopname',
            'email' => 'required|email|unique:shopvendors,email',
            'telephone' => 'required|unique:shopvendors,telephone|numeric|min:10',
            'location' => 'required|string',
            'region' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            "pob" => "required|image|mimes:png,jpeg,jpg|max:2048"
        ]);

        if ($validate->fails()) {
            return response()->json(
                $validate->errors(),
                400
            );
        }


        if ($request->hasFile("pob")) {
            $pathTUploadFile = $request->file("pob")->store("proof_of_business", "public");
        }


        $vendor_pob = new ProofOfBusiness();

        $saveVendor = ShopVendor::create([
            "shopname" => $request->shopname,
            "email" => $request->email,
            "telephone" => $request->telephone,
            "location" => $request->location,
            "region" => $request->region,
            "password" => Hash::make($request->password)
        ]);



        //if registration successfull
        if ($saveVendor) {

            $vendor_pob->shopvendor_id = $saveVendor->id;
            $vendor_pob->proof_of_business = $pathTUploadFile;

            $save_vendor_pob = $vendor_pob->save();

            if (!$save_vendor_pob) {
                return response()->json(["error" => "failed to register vendor"], 500);
            }

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

        // $session = new Session();
        // $session->start();

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
            ], 403);
        }



        //revoke all token
        $vendordata->tokens()->delete();

        //generate token for vendor

        $accessToken = $vendordata->createToken($request->email)->plainTextToken;

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
