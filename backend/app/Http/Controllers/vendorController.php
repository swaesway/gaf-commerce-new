<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ShopVendor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\Builder\Function_;

use function Illuminate\Log\log;

class vendorController extends Controller
{

    public function Vendor(Request $request)
    {
        $vendor = $request->user();

        if (!$vendor) {
            return response()->json([
                'message' => 'User not authenticated',
            ], 401);
        }

        return response()->json($vendor, 200);
    }
    //function to add products
    public function addproduct(Request $request)
    {
        //fetch current details of user 
        $vendor = $request->user();
        //generate unique id's for products
        $productid = str()->uuid();


        $validate = Validator::make($request->all(), [
            'title' => 'required|string|min:7',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'description' => 'required|string',
            "images" => 'required|array|max:4',
            "images.*" => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);


        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }


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

        // return response()->json(["message" => "hello"], 201);


        if ($request->hasFile("images")) {
            foreach ($request->file("images") as $file) {

                $pathToUploadFile = $file->store("product-images", "public");

                if (!$pathToUploadFile) {
                    return response()->json("Failed to upload product-images", 403);
                }

                $fileExtension = $file->getMimeType();

                $product_image = new ProductImage();

                $product_image->product_id = $product->id;
                $product_image->image = $pathToUploadFile;
                $product_image->type = $fileExtension;

                $saveProductImage = $product_image->save();

                if (!$saveProductImage) {
                    return response()->json("Failed to save product images", 500);
                }
            };

            return response()->json([
                'message' => 'Product  uploaded successfully',
                'product' => $product
            ], 201);
        }

        //return if products added
        return response()->json(['message' => 'Product added successfully', 'product' => $product], 201);
    }

    public function viewproduct($id)
    {
        $vendor = request()->user();
        $product = Product::where('shopvendor_id', $vendor->id)
            ->where('id', $id)
            ->get()->first();
        if (!$product) {
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

    public function updateproduct(Request $request, $id)
    {
        $vendor = $request->user();

        //validate inputs

        $validate = Validator::make($request->all(), [
            'title' => 'required|string|min:7',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'description' => 'required|string',
            "images" => 'required|array|max:4',
            "images.*" => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);


        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }


        $product = Product::where('id', $id)->where('shopvendor_id', $vendor->id)->first();

        if (!$product) {
            return response()->json([
                'message' => 'No Product found'
            ], 404);
        }

        $Productedit = $product->update([
            'title' => $request->title,
            'price' => $request->price,
            'category' => $request->category,
            'description' => $request->description,
        ]);


        if ($request->hasFile("images")) {
            $deleteProductImages = ProductImage::where("proudct_id", $product->id)->delete();

            if (!$deleteProductImages) {
                return response()->json([
                    'message' => 'Failed to delete product images',
                ], 500);
            }

            foreach ($request->file("images") as $file) {
                $pathToUploadFile = $file->store("product-images", "public");

                if (!$pathToUploadFile) {
                    return response()->json("Failed to upload product-images", 403);
                }

                $fileExtension = $file->getMimeType();

                $product_image = new ProductImage();

                $product_image->product_id = $product->id;
                $product_image->image = $pathToUploadFile;
                $product_image->type = $fileExtension;

                $saveProductImage = $product_image->save();

                if (!$saveProductImage) {
                    return response()->json("Failed to save product images", 500);
                }
            }
        }

        if ($Productedit) {
            # code...
            return response()->json([
                'message' => 'Product updated successfully!'
            ], 210);
        } else {
            return response()->json([
                'message' => 'An error occured updating product'
            ], 404);
        }
    }


    public function deleteproduct($id)
    {
        $vendor = request()->user();

        $product = Product::where('id', $id)->where('shopvendor_id', $vendor->id)->first();
        $productImages = ProductImage::where("product_id", $id)->get();

        if (!$product) {
            return response()->json([
                'message' => 'No Product found'
            ], 404);
        }

        $deleteProduct = $product->delete();

        if (!$deleteProduct) {
            return response()->json([
                'message' => 'An error occured while deleting product'
            ], 404);
        }

        foreach ($productImages as $image) {
            $productImageExist = Storage::disk("public")->exists($image["image"]);

            if ($productImageExist) {
                Storage::disk("public")->delete($image["image"]);
            }
        }

        return response()->json([
            'message' => 'Product deleted successfully!'
        ], 210);
    }

    public function previewProductImage(Request $request)
    {
        if (!$request->query("image")) {
            return response()->json(["message" => "image query needed to get image"], 400);
        }


        $productImage = ProductImage::where("image", $request->query("image"))->first();

        if (!$productImage) {
            return response()->json(["message" => "Image not found"], 404);
        }

        $imagePath = $productImage->image;

        if (!Storage::disk("public")->exists($imagePath)) {
            return response()->json(["messge" => "Image not found on disk storage"], 404);
        };

        return response()->file(Storage::disk("public")->path($imagePath));
    }

    public function vendorlogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 201
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

    public function searchProductByPriceAndCategory(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'price_range' => 'array',
            'categories' => 'array',
            "sortBy" => "string",
            "all_price_range" => 'string',
            "all_categories" => 'string'

        ]);

        // if (!$request->price_range && !$request->categories) {
        //     $products = Product::all();

        //     return response()->json($products, 200);
        // }


        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $product = Product::with("images")->filterByPriceAndCategory($request->price_range, $request->categories, $request->sortBy, $request->all_price_range, $request->all_categories)->get();

        if (!$product) {
            return response()->json(["message" => "No Product found in this price range and categories"], 404);
        }

        return response()->json($product, 200);
    }
    public function searchByProduct(Request $request)
    {
        $products = Product::with("images")->get();

        if ($request->search == "") {
            return response()->json($products, 200);
        }

        $products = Product::with("images")->searchItem($request->search)->get();

        return response()->json($products, 200);
    }

    public function searchProductByCategory(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'categories' => 'required|array|min:1',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }



        $products = Product::searchCategory($request->categories)->get();

        if ($products->isEmpty()) {
            return response()->json(["message" => "No product category search found for these categories in " . implode(", ", $request->categories)], 404);
        }

        return response()->json(["message" => $products], 200);
    }

    public function searchProductByPriceRange(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'price_range' => 'required|array|min:1',
            "all_prices" => "nullable|string"
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }


        if (!empty($request->all_prices)) {
            $products = Product::searchPrice($request->price_range, $request->all_prices)->get();
        } else {
            $products = Product::searchPrice($request->price_range)->get();
        }


        if ($products->isEmpty()) {
            return response()->json(["message" => "No product found within the price range of $request->price_range[0] to $request->price_range[1]"], 404);
        }

        return response()->json(["products" => $products]);
    }
}
