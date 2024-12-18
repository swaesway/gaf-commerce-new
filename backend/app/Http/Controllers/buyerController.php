<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShopVendor;
use Illuminate\Http\Request;

class buyerController extends Controller
{
    //
    public function home()
    {
        $products = Product::where('frozen', 0)->get();
        return response()->json(
           ['products' => $products]
        );
    }

    public function viewmoreinfo($id)
    {
        $buyer = Request()->user();

        $productinfo = Product::find($id);

        if($productinfo)
        {
            $shopdetails = ShopVendor::find($productinfo->shopvendor_id);
            return response()->json(
                [
                    'productinfo' => [
                        'title' => $productinfo->title,
                        'description' => $productinfo->description,
                        'price' => $productinfo->price,
                    ],
                    'vendorinfo' => [
                        'shopname' => $shopdetails->shopname,
                        'email' => $shopdetails->email,
                        'telephone' => $shopdetails->telephone,
                        'location' => $shopdetails->location,
                        'region' => $shopdetails->region
                    ]
                ]
                );
        }

       

    }

    public function userlogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); 
        return response()->json([
            'status'=>201
        ], 201);
    }

    public function chatvendor(Request $request, $id)
    {

    }
}
