<?php

namespace App\Http\Controllers;

use App\Models\ShopVendor;
use App\Models\User;
use Illuminate\Http\Request;

class adminController extends Controller
{
    //
    public function viewvendors()
    {
        $vendors = ShopVendor::all();

        return response()->json([
            'vendors' => $vendors
        ]);

    }

    public function viewusers()
    {
        $users = User::all();

        if($users)
        {
            return response()->json([
                'users' => $users
            ]);
        }
    }

    public function approveVendor($id)
    {
        
        $vendor = ShopVendor::find($id);

        if($vendor) {
            $vendor->approved = '1'; 
            $vendor->save(); 
        
            return response()->json([
                'message' => 'Vendor approved successfully',
                'vendor' => $vendor
            ]);
        } else {
            return response()->json([
                'message' => 'Vendor not found'
            ], 404);
        }
    }

    public function blockVendor($id)
    {
        $vendor = ShopVendor::find($id);


        if($vendor)
        {
            $vendor->blockedstatus = '1';
            $vendor->save();

            if ($vendor) {
                # code...
                return response()->json([
                    'status' => 201,
                    'message' => 'Vendor has been blocked successfully'
                ], 201);
            }
            
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => '  No vendor was found'
            ], 404);
        }
    }

    public function unblockVendor($id)
    {
        
        $vendor = ShopVendor::where();


        if($vendor)
        {
            $vendor->blockedstatus = '0';
            $vendor->save();

            if ($vendor) {
                # code...
                return response()->json([
                    'status' => 201,
                    'message' => 'Vendor has been unblocked successfully'
                ], 201);
            }
            
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => '  No vendor was found'
            ], 404);
        }
    }
}
