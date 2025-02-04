<?php

namespace App\Http\Controllers;

use App\Mail\contactMail;
use App\Models\Rating;
use App\Models\Report;
use App\Models\Product;
use App\Models\Callback;
use App\Models\Wishlist;
use App\Models\ShopVendor;
use App\Models\Serviceinfo;
use Illuminate\Http\Request;
// use Framework\Session\Session;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

    public function user()
    {
        $AuthUser = Auth::user();

        if (!$AuthUser) {
            return response()->json(["message" => "Unauthorized user"], 401);
        }

        $user = Serviceinfo::where("servicenumber", $AuthUser->servicenumber)->firstOrFail();

        return response()->json($user, 200);
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
        // $session = new Session();
        // $session->start();

        $request->user()->currentAccessToken()->delete();
        // $session->destroy();

        return response()->json([
            "message" => "User logged out successfully"
        ], 200);
    }

    public function chatvendor(Request $request, $id) {}

    public function getAllProducts()
    {
        $products = Product::allProduct()->oldest()->take(4)->get();

        if ($products->isEmpty()) {
            return response()->json(["message" => "No products"], 403);
        }

        return response()->json($products, 200);
    }

    public function getProduct($productId)
    {

        if (empty($productId)) {
            return response()->json(['message' => 'Product ID is required.'], 400);
        }

        $product = Product::with("shopvendor", "images", "ratings")->find($productId);

        if (!$product) {
            return response()->json(['message' => 'Product Not Found'], 404);
        }

        $product->totalProductRating = $product->totalProductRating();


        return response()->json($product, 200);
    }


    public function latestProducts()
    {
        $products = Product::allLatestProduct()->latest()->take(4)->get();

        if ($products->isEmpty()) {
            return response()->json(["message" => "No products"], 403);
        }


        return response()->json($products, 200);
    }

    public function getSimilarProducts(Request $request)
    {

        // if (empty($category)) {
        //     return response()->json(["message" => "All fields are required"], 400);
        // }

        $product = Product::similarProduct($request->title, $request->description)->take(12)->get();
        return response()->json($product, 200);
    }

    public function getFilteredProducts(Request $request)
    {
        if ($request->price_range || $request->categories) {

            $products = Product::filterByPriceAndCategory($request->price_range, $request->categories)->get();

            if ($products->isEmpty()) {
                return response()->json(["message" => "No products found in this price range and categories"], 404);
            }

            return response()->json($products, 200);
        }
    }

    public function getWishList(Request $request)
    {
        // $session = new Session();
        // $session->start();

        // if (!$session->isUserAuthenticated && $session->wishList) {

        //     $wishList = (array) $session->get("wishList");

        //     $product = Product::wishListProduct($wishList)->get();

        //     if ($product->isEmpty()) {
        //         return response()->json(["error" => "Product not found"], 400);
        //     }

        //     return response()->json([
        //         "wishList" => $product
        //     ], 200);
        // }

        // if ($session->isUserAuthenticated && !$session->wishList) {
        //     $wishList =  Serviceinfo::find($session->isUserAuthenticated)->wishlist;

        //     $product = Product::wishListProduct($wishList)->get();

        //     if ($product->isEmpty()) {
        //         return response()->json(["error" => "Product not found"], 400);
        //     }


        //     return response()->json([
        //         "wishList" => $product
        //     ], 200);
        // }

        // $wishList =  Serviceinfo::find(Auth::id())->wishlist;

        // if ($wishList->isEmpty()) {
        //     return response()->json($wishList, 200);
        // }


        $product = Wishlist::wishlistProduct()->where("wishlists.servicenumber", Auth::id())->orderBy("created_at")->get();


        return response()->json($product, 200);
    }

    public function getProductRatings($productId)
    {

        if (empty($productId)) {
            return response()->json(['message' => 'Product ID is required.'], 400);
        }

        $product = Rating::with("product", "serviceinfos")->where('product_id', $productId)->get();

        if (!$product) {
            return response()->json(['message' => 'No ratings found for this product.'], 404);
        }

        return response()->json($product, 200);
    }

    public function addProductToWishlist(Request $request, $productId)
    {

        // $validate = Validator::make($request->all(), [
        //     'servicenumber' => 'required|size:6',
        // ]);

        // if ($validate->fails()) {
        //     return response()->json(
        //         $validate->errors(),
        //         400
        //     );
        // }

        if (empty($productId)) {
            return response()->json(['message' => 'Product ID is required.'], 400);
        }

        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        // $storeWishList = $session->get("wishList", []);



        // if (!$session->isUserAuthenticated) {

        //     $wishlist = [
        //         "servicenumber" => null,
        //         "product_id" => $product->id
        //     ];

        //     if (!in_array($wishlist, (array) $storeWishList)) {
        //         $storeWishList[] = $wishlist;
        //         $session->set("wishList", $storeWishList);

        //         return response()->json("$product->title added to wishlist", 201);
        //     }

        //     return response()->json("$product->title already added to wishlist", 400);
        // }

        // if ($session->isUserAuthenticated && $session->wishList) {
        //     foreach ($session->wishList as $wishList) {
        //         $wishList["servicenumber"] = $session->serviceNumber;
        //         Wishlist::create($wishList);
        //     }

        //     $session->remove("wishList");

        //     $isWishListPresent = Wishlist::where("servicenumber", $session->serviceNumber)
        //         ->where("product_id", $product->id)
        //         ->first();

        //     if ($isWishListPresent) {
        //         return response()->json("Wishlist is available already in your wishlist", 400);
        //     }

        //     Wishlist::create([
        //         "servicenumber" => $session->serviceNumber,
        //         "product_id" => $product->id
        //     ]);
        // } else {

        //     $isWishListPresent = Wishlist::where("servicenumber", $session->serviceNumber)
        //         ->where("product_id", $product->id)
        //         ->first();

        //     if ($isWishListPresent) {
        //         return response()->json("Wishlist is available already in your wishlist", 400);
        //     }

        //     Wishlist::create([
        //         "servicenumber" => $session->serviceNumber,
        //         "product_id" => $product->id
        //     ]);

        //     return response()->json("$product->title added to wishlist", 201);
        // }

        $isWishListPresent = Wishlist::where("servicenumber", Auth::id())
            ->where("product_id", $product->id)
            ->exists();

        if ($isWishListPresent) {
            return response()->json(["message" => "product already in wishlist"], 400);
        }

        Wishlist::create([
            "servicenumber" => Auth::id(),
            "product_id" => $product->id
        ]);

        return response()->json("added to wishlist", 201);
    }

    public function removeProductFromWishlist(Request $request, $productId)
    {

        // $session = new Session();
        // $session->start();

        // $validate = Validator::make($request->all(), [
        //     'servicenumber' => 'required|size:6',
        // ]);

        // if ($validate->fails()) {
        //     return response()->json(
        //         $validate->errors(),
        //         400
        //     );
        // }

        if (empty($productId)) {
            return response()->json("Product ID required", 400);
        }

        // $product = Product::find($productId);

        // if (!$product) {
        //     return response()->json("Product not found", 404);
        // }


        // if ($session->isUserAuthenticated && $session->serviceNumber && !$session->wishList) {

        //     $foundWishList = Wishlist::where("servicenumber", $session->serviceNumber)
        //         ->where("product_id", $product->id)
        //         ->first();

        //     if (!$foundWishList) {
        //         return response()->json(["error" => "Wishlist not found"], 404);
        //     }

        //     $foundWishList->delete();

        //     return response()->json(["message" => "Wishlist deleted successfully"], 200);
        // }

        // $wishlistStore = (array) $session->get("wishList");

        // $deleteWishList = array_filter($wishlistStore, function ($wishList) use ($product) {
        //     return $wishList["product_id"] !== $product->id;
        // });

        // $session->set("wishList", $deleteWishList);

        // return response()->json($session->get("wishList"), 200);

        $foundWishList = Wishlist::where("servicenumber", Auth::id())
            ->where("product_id", $productId)
            ->first();

        if (!$foundWishList) {
            return response()->json(["error" => "Wishlist not found"], 404);
        }

        $foundWishList->delete();

        return response()->json(["message" => "Wishlist deleted successfully"], 200);
    }

    public function productRating(Request $request, $productId)
    {
        // $session = new Session();
        // $session->start();

        if (empty($productId)) {
            return response()->json(["message" => "Product ID is required."], 400);
        }

        $validate = Validator::make($request->all(), [
            // 'servicenumber' => 'required|size:6',
            'rating' => 'required|numeric|between:1,5',
            "comment" => "required|string",
            "shopvendor_id" => "required|string"
        ]);


        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        // $product = Product::find($productId);

        // if (!$product) {
        //     return response()->json(["error" => "Product not found"], 404);
        // }

        $foundRatedUser = Rating::where("servicenumber", Auth::id())
            ->where("product_id", $productId)
            ->exists();

        if ($foundRatedUser) {
            return response()->json(["message" => "You have already rated this product"], 400);
        }


        $rateProduct = new Rating();

        $rateProduct->servicenumber = Auth::id();
        $rateProduct->product_id = $productId;
        $rateProduct->rating = $request->rating;
        $rateProduct->comment = $request->comment;
        $rateProduct->shopvendor_id = $request->shopvendor_id;


        $saveRatedProduct = $rateProduct->save();

        if (!$saveRatedProduct) {
            return response()->json(["message" => "Failed to rate the product"], 500);
        }

        return response()->json(["message" => "Product rated successfully"], 201);
    }

    public function requestCallback(Request $request, $productId)
    {


        if (empty($productId)) {
            return response()->json(["message" => "Product is required"], 400);
        }

        // $product = Product::find($productId);

        // if (!$product) {
        //     return response()->json(["message" => "Product not found"], 404);
        // }


        $foundCallback = Callback::where("servicenumber", Auth::id())->where("product_id", $productId)->first();

        if ($foundCallback) {

            $removeCallback = $foundCallback->delete();

            if (!$removeCallback) {
                return response()->json(["message" => "Failed to cancel callback"], 500);
            }

            return response()->json(["message" => "Callback cancelled successfully"], 200);
        }

        $callback = new Callback();
        $callback->servicenumber = Auth::id();
        $callback->shopvendors_id = $request->shopvendor_id;
        $callback->product_id_images = $productId;
        $callback->product_id = $productId;

        $saveCallback = $callback->save();

        if (!$saveCallback) return response()->json(["message" => "Failed to request callback"], 500);

        return response()->json(["message" => "callback requested successfully"], 201);
    }

    public function reportProduct($productId)
    {
        if (empty($productId)) {
            return response()->json(["message" => "Product is required"], 400);
        }

        // $product = Product::find($productId);

        // if (!$product) {
        //     return response()->json(["message" => "Product not found"], 404);
        // }

        $foundReport = Report::where("servicenumber", Auth::id())->where("product_id", $productId)->exists();

        if ($foundReport) {
            return response()->json(["message" => "product have been reported already"], 200);
        }

        $report = new Report();
        $report->servicenumber = Auth::id();
        $report->product_id = $productId;

        $saveReport = $report->save();

        if (!$saveReport) return response()->json(["message" => "Failed to report product"], 500);

        return response()->json(["message" => "product report has been recorded"], 201);
    }


    public function contactAdmin(Request $request)
    {

        $validate = Validator::make($request->all(), [
            "subject" => "required|string|max:100",
            "message" => "required|string",
        ]);



        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json("Unauthorized", 401);
        }

        $serviceInfo = Serviceinfo::where("servicenumber", $user->servicenumber)->firstOrFail();

        $fromEmail = $serviceInfo->email;
        $to = "codefuseini176@gmail.com";
        $subject = $request->subject;
        $message = $request->message;

        Mail::alwaysFrom($fromEmail);
        Mail::to($to)->send(new contactMail($subject, $message, $fromEmail));

        return response()->json("Thank you for contacting us. Our team will get back to you soon.", 200);
    }
}
