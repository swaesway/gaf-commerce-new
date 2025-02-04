<?php

namespace App\Http\Controllers;

use App\Models\Callback;
use App\Models\Product;
use App\Models\ShopVendor;
use App\Models\ProductImage;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use function Illuminate\Log\log;
use PhpParser\Builder\Function_;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
            'title' => 'required|string',
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

        // return response()->json(["message" => "hello"], 201)


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

    public function getVendorProductById($id)
    {
        $vendor = request()->user();
        $product = Product::where('shopvendor_id', $vendor->id)
            ->where('id', $id)
            ->where("frozen", '!=', "1")
            ->get()->first();
        if (!$product) {
            return response()->json([
                'message' => 'No Product found'
            ], 404);
        }

        return response()->json($product, 200);
    }

    public function viewproducts()
    {
        //fetch current user detials
        $vendor = request()->user();

        if (!$vendor) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        //fetch products with corresponding id of vendor
        $vendorProducts = Product::where('shopvendor_id', $vendor->id)->get();

        //return true if found 
        return response()->json($vendorProducts, 200);
    }


    public function getVendorCallbacks()
    {

        $vendor = Auth::user();
        $callback = Callback::myCallbacks()->where("shopvendors_id", $vendor->id)->where("hide", "=",  0)->get();
        return response()->json($callback);
    }

    public function getVendorCallbackById($id)
    {

        if (empty($id)) {
            return response()->json(['message' => 'Callback ID is required.'], 400);
        }

        $vendor = Auth::user();

        $callback = Callback::myCallbacks()->where("callbacks.id", $id)->where("hide", "=", 0)->where("shopvendors_id", $vendor->id)->get();

        if ($callback->isEmpty()) {
            return response()->json(['message' => 'No Callback found for this ID.'], 404);
        }

        return response()->json($callback);
    }

    public function updateproduct(Request $request, $id)
    {

        $vendor = $request->user();

        //validate inputs

        $validate = Validator::make($request->all(), [
            'title' => 'required|string',
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

            $deleteProductImages = ProductImage::where("product_id", $product->id)->delete();
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
            ], 201);
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

        return response()->json('Product deleted successfully!', 200);
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

    public function vendorLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json("You have successfully logged out", 200);
    }

    public function freezeproduct(Request $request, $id)
    {
        $vendor = $request->user();
        $product = Product::where('id', $id,)->where('shopvendor_id', $vendor->id)->first();

        if (!$product) {
            # code...
            return response()->json([
                'message' => 'No product was found'
            ], 404);
        }

        if ($product->frozen == "1") {
            $product->frozen = "0";
            $product->save();

            return response()->json(["message" => "Product unfrozen ❄️! "], 201);
        }

        $product->frozen = '1';
        $product->save();

        return response()->json([
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


    public function todaysProduct()
    {

        $vendor = Auth::user();

        $todaysProductCount = Product::where("shopvendor_id", $vendor->id)->whereDate('created_at', Carbon::today())->count();
        $yesterdaysProductCount = Product::where("shopvendor_id", $vendor->id)->whereDate('created_at', Carbon::yesterday())->count();

        $percentageChangeT = 0;
        $percentageChangeY = 0;
        if ($yesterdaysProductCount > 0) {
            $percentageChangeY = (($todaysProductCount - $yesterdaysProductCount) / $yesterdaysProductCount) * 100;
        } else {
            // If there were no products yesterday, any new products today mean a 100% increase
            $percentageChangeT = $todaysProductCount > 0 ? 100 : 0;
        }

        return response()->json([
            'percentageChangeY' => round($percentageChangeY, 2),
            'percentageChangeT' => round($percentageChangeT, 2)
        ], 200);
    }

    public function monthsProduct()
    {

        $vendor = Auth::user();


        $thisMonthsProductCount = Product::where("shopvendor_id", $vendor->id)->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
        $lastMonthsProductCount = Product::where("shopvendor_id", $vendor->id)->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->count();

        $percentageChangeT = 0;
        $percentageChangeY = 0;

        if ($lastMonthsProductCount > 0) {

            if ($lastMonthsProductCount === 0) {
                $percentageChangeY = 0;
            } else {
                $percentageChangeY = (($thisMonthsProductCount - $lastMonthsProductCount) / $lastMonthsProductCount) * 100;
            }
        } else {
            // If there were no products yesterday, any new products today mean a 100% increase
            $percentageChangeT = $thisMonthsProductCount > 0 ? 100 : 0;
        }

        return response()->json([
            'percentageChangeY' => round($percentageChangeY, 2),
            'percentageChangeT' => round($percentageChangeT, 2)
        ], 200);
    }
    public function yearsProduct()
    {

        $vendor = Auth::user();


        $thisYearsProductCount = Product::where("shopvendor_id", $vendor->id)->whereYear('created_at', Carbon::now()->year)->count();
        $lastYearsProductCount = Product::where("shopvendor_id", $vendor->id)->whereYear('created_at', Carbon::now()->subYear()->year)->count();

        $percentageChangeT = 0;
        $percentageChangeY = 0;

        if ($lastYearsProductCount > 0) {
            if ($lastYearsProductCount === 0) {
                $percentageChangeY = 0;
            } else {
                $percentageChangeY = (($$thisYearsProductCount - $lastYearsProductCount) / $lastYearsProductCount) * 100;
            }

            $percentageChangeY = (($thisYearsProductCount - $lastYearsProductCount) / $lastYearsProductCount) * 100;
        } else {
            // If there were no products yesterday, any new products today mean a 100% increase
            $percentageChangeT = $thisYearsProductCount > 0 ? 100 : 0;
        }

        return response()->json([
            'percentageChangeY' => round($percentageChangeY, 2),
            'percentageChangeT' => round($percentageChangeT, 2)
        ], 200);
    }


    public function callbackStatus(Request $request, $id)
    {
        if (empty($id)) {
            return response()->json(['message' => 'Product id is required'], 400);
        }

        if (!$request->query("status")) {
            return response()->json(['message' => 'Status is required'], 400);
        }


        $vendor = Auth::user();

        $callback = Callback::where("id", $id)->where("shopvendors_id", $vendor->id)->first();

        if (!$callback) {
            return response()->json(['message' => 'No callback found for this product'], 404);
        }


        if ($request->query("status") === "0401") {

            $callback->update([
                "status" => $request->query("status")
            ]);

            return response()->json('Callback declined successfully', 200);
        }

        if ($request->query("status") === "1010") {
            $callback->update([
                "status" => $request->query("status")
            ]);

            return response()->json('Callback approved successfully', 200);
        }
    }


    public function hideCallback($id)
    {

        if (empty($id)) {
            return response()->json(['message' => 'Callback id is required'], 400);
        }

        $vendor = Auth::user();

        $callback = Callback::where("id", $id)->where("shopvendors_id", $vendor->id)->first();

        if (!$callback) {
            return response()->json(['message' => 'No callback found for this product'], 404);
        }

        $callback->hide = 1;
        $callback->save();

        return response()->json('Callback hidden successfully', 200);
    }

    public function viewCallback($id)
    {
        if (empty($id)) {
            return response()->json(["message" => "Callback ID is required"], 400);
        }

        $vendor = Auth::user();

        $callback = Callback::where("id", $id)->where("shopvendors_id", $vendor->id)->first();

        if (!$callback) {
            return response()->json(["message" => "No callback found for this product"]);
        }

        $callback->view = 1;
        $callback->save();

        return response()->json("viewed callback ID of " . $id, 200);
    }

    public function averageProductRating()
    {
        $vendor = Auth::user();

        $totalAverageRating = Rating::where("shopvendor_id", $vendor->id)->avg("ratings.rating");

        return response()->json($totalAverageRating, 200);
    }
}
