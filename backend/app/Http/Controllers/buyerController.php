<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rating;
use App\Models\Wishlist;
use App\Models\ShopVendor;
use App\Models\Serviceinfo;
use Illuminate\Http\Request;
use Framework\Session\Session;

use Illuminate\Support\Facades\Validator;


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

        if ($productinfo) {
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
        $session = new Session();
        $session->start();

        $request->user()->currentAccessToken()->delete();
        $session->destroy();

        return response()->json([
            "message" => "User logged out successfully"
        ], 200);
    }

    public function chatvendor(Request $request, $id) {}

    public function getAllProducts()
    {
        $products = Product::all();
        return response()->json(['products' => $products]);
    }

    public function getWishList()
    {
        $session = new Session();
        $session->start();

        if (!$session->isUserAuthenticated && $session->wishList) {

            $wishList = (array) $session->get("wishList");

            $product = Product::wishListProduct($wishList)->get();

            if ($product->isEmpty()) {
                return response()->json(["error" => "Product not found"], 400);
            }

            return response()->json([
                "wishList" => $product
            ], 200);
        }

        if ($session->isUserAuthenticated && !$session->wishList) {
            $wishList =  Serviceinfo::find($session->isUserAuthenticated)->wishlist;

            $product = Product::wishListProduct($wishList)->get();

            if ($product->isEmpty()) {
                return response()->json(["error" => "Product not found"], 400);
            }


            return response()->json([
                "wishList" => $product
            ], 200);
        }

        return response()->json([
            "wishList" => []
        ], 200);
    }

    public function getProductRatings($productId)
    {

        if (empty($productId)) {
            return response()->json(['message' => 'Product ID is required.'], 400);
        }

        $product = Product::find($productId)->ratings;

        if (!$product) {
            return response()->json(['message' => 'No ratings found for this product.'], 404);
        }

        return response()->json([
            "ratings" => $product
        ], 200);
    }

    public function addProductToWishlist(Request $request, $productId)
    {
        $session = new Session();
        $session->start();

        if (empty($productId)) {
            return response()->json(['message' => 'Product ID is required.'], 400);
        }

        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $storeWishList = $session->get("wishList", []);



        if (!$session->isUserAuthenticated) {

            $wishlist = [
                "servicenumber" => null,
                "product_id" => $product->id
            ];

            if (!in_array($wishlist, (array) $storeWishList)) {
                $storeWishList[] = $wishlist;
                $session->set("wishList", $storeWishList);

                return response()->json("$product->title added to wishlist", 201);
            }

            return response()->json("$product->title already added to wishlist", 400);
        }

        if ($session->isUserAuthenticated && $session->wishList) {
            foreach ($session->wishList as $wishList) {
                $wishList["servicenumber"] = $session->serviceNumber;
                Wishlist::create($wishList);
            }

            $session->remove("wishList");

            $isWishListPresent = Wishlist::where("servicenumber", $session->serviceNumber)
                ->where("product_id", $product->id)
                ->first();

            if ($isWishListPresent) {
                return response()->json("Wishlist is available already in your wishlist", 400);
            }

            Wishlist::create([
                "servicenumber" => $session->serviceNumber,
                "product_id" => $product->id
            ]);
        } else {

            $isWishListPresent = Wishlist::where("servicenumber", $session->serviceNumber)
                ->where("product_id", $product->id)
                ->first();

            if ($isWishListPresent) {
                return response()->json("Wishlist is available already in your wishlist", 400);
            }

            Wishlist::create([
                "servicenumber" => $session->serviceNumber,
                "product_id" => $product->id
            ]);

            return response()->json("$product->title added to wishlist", 201);
        }
    }

    public function removeProductFromWishlist($productId)
    {

        $session = new Session();
        $session->start();

        if (empty($productId)) {
            return response()->json("Product ID required", 400);
        }

        $product = Product::find($productId);

        if (!$product) {
            return response()->json("Product not found", 404);
        }


        if ($session->isUserAuthenticated && $session->serviceNumber && !$session->wishList) {

            $foundWishList = Wishlist::where("servicenumber", $session->serviceNumber)
                ->where("product_id", $product->id)
                ->first();

            if (!$foundWishList) {
                return response()->json(["error" => "Wishlist not found"], 404);
            }

            $foundWishList->delete();

            return response()->json(["message" => "Wishlist deleted successfully"], 200);
        }

        $wishlistStore = (array) $session->get("wishList");

        $deleteWishList = array_filter($wishlistStore, function ($wishList) use ($product) {
            return $wishList["product_id"] !== $product->id;
        });

        $session->set("wishList", $deleteWishList);

        return response()->json($session->get("wishList"), 200);
    }

    public function productRating(Request $request, $productId)
    {
        $session = new Session();
        $session->start();

        $validate = Validator::make($request->all(), [
            'rating' => 'required|numeric|between:1,5',
            "comment" => "required|string"
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $product = Product::find($productId);

        if (!$product) {
            return response()->json(["error" => "Product not found"], 404);
        }

        $foundRatedUser = Rating::where("servicenumber", $session->serviceNumber)
            ->where("product_id", $product->id)
            ->first();

        if ($foundRatedUser) {
            return response()->json(["error" => "You have already rated this product"], 400);
        }



        $rateProduct = new Rating();

        $rateProduct->servicenumber = $session->serviceNumber;
        $rateProduct->product_id = $product->id;
        $rateProduct->rating = $request->rating;
        $rateProduct->comment = $request->comment;

        $saveRatedProduct = $rateProduct->save();

        if (!$saveRatedProduct) {
            return response()->json(["error" => "Failed to rate the product"], 500);
        }

        return response()->json(["message" => "Product rated successfully"], 200);
    }
}
