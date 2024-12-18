<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShopVendor;
use Illuminate\Http\Request;
use PhpParser\Builder\Function_;

class vendorController extends Controller
{
    //function to add products
    public function addproduct(Request $request)
    {   
        //fetch current details of user 
        $vendor = $request->user();
        //generate unique id's for products
        $productid = str()->uuid();

        //validate input data
        $request->validate([
            'title' => 'required|string|min:7',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'description' => 'required|string',
        ]);


        //if no vendor found return fasle
        if (!$vendor) {
            return response()->json([
                'message' => 'Vendor not found', 
            ], 404);
        }

        //receive inputs for product
        $product = $vendor->products()->create([
            'title' => $request->title,
            'price' => $request->price,
            'category' => $request->category,
            'description' => $request->description,
            'productid' => $productid,
            'shopvendor_id' => $vendor->id
        ]);


        //return if products added
        return response()->json(['message' => 'Product added successfully', 'product' => $product], 201);

    }

    public function viewproduct($id)
    {
        $vendor = request()->user();
        $product = Product::where('shopvendor_id', $vendor->id)
                            ->where('id', $id)
                            ->get()->first();
        if(!$product)
        {
            return response()->json([
                'status' => 404,
                'message' => 'No Product found'
            ], 404);
        }

        return response()->json([
            'status' => 201,
            'product' => $product
        ], 201);
                        
    }

    public function viewproducts()
    {
        //fetch current user detials
        $vendor = request()->user();

        //fetch products with corresponding id of vendor
        $vendorProducts = Product::where('shopvendor_id', $vendor->id)->get();
    
        //return true if found 
        return response()->json([
            'products' => $vendorProducts,
        ]);
    }

    public function updateproduct(Request $request,$id)
    {
        $vendor = $request->user();

        //validate inputs
        $request->validate([
            'title' => 'required|string|min:7',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'description' => 'required|string',
        ]);

        $product = Product::where('id', $id)->where('shopvendor_id', $vendor->id)->first();

        if(!$product)
        {
            return response()->json([
                'status' => 422,
                'message' => 'No Product found'
            ], 422);
        }
        
        $Productedit = $product->update([
            'title' => $request->title,
            'price' => $request->price,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        if ($Productedit) {
            # code...
            return response()->json([
                'status' => 201,
                'message' => 'Product updated successfully!'
            ], 210);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'An error occured updating product'
            ], 404);
        }


    }


    public function deleteproduct($id)
    {
        $vendor = request()->user();

        $product = Product::where('id', $id)->where('shopvendor_id', $vendor->id)->first();

        if(!$product)
        {
            return response()->json([
                'status' => 422,
                'message' => 'No Product found'
            ], 422);
        }
    
        if ($product->delete()) {
            # code...
            return response()->json([
                'status' => 201,
                'message' => 'Product deleted successfully!'
            ], 210);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'An error occured deleting product'
            ], 404);
        }


    }

    public function vendorlogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); 
        return response()->json([
            'status'=>201
        ], 201);
    } 

    public function freezeproduct(Request $request, $id)
    {
        $vendor = $request->user();
        $product = Product::where('id', $id,)->where('shopvendor_id', $vendor->id)->first();

        if (!$product) {
            # code...
            return response()->json([
                'status' => 404,
                'message' => 'No product was found'
            ], 404);
        }

        $product->frozen = '1';
        $product->save();

        return response()->json([
            'status' => 201,
            'message' => 'Product frozen ❄️! '
        ], 201);
    }

    public function unfreezeproduct(Request $request, $id)
    {
        $vendor = $request->user();
        $product = Product::where('id', $id,)->where('shopvendor_id', $vendor->id)->first();

        if (!$product) {
            # code...
            return response()->json([
                'status' => 404,
                'message' => 'No product was found'
            ], 404);
        }

        $product->frozen = '0';
        $product->save();

        return response()->json([
            'status' => 201,
            'message' => 'Product unfrozen ❄️! '
        ], 201);
    }
}
