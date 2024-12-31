<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\buyerController;
use App\Http\Controllers\LoginModelController;
use App\Http\Controllers\vendorController;
use Illuminate\Support\Facades\Route;



//user login endpoints  
Route::post('user/login', [LoginModelController::class, 'login']);
Route::post('user/login/verify', [LoginModelController::class, 'verify']);


//vendor endpoints
Route::post('vendor/register', [LoginModelController::class, 'registervendor']);
Route::post('vendor/login', [LoginModelController::class, 'vendorlogin']);

//admin endpoints
Route::post('admin/login', [LoginModelController::class, 'adminlogin']);
Route::post('admin/logout', [LoginModelController::class, 'adminlogout']);

//auth sanctum routes for vendor
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('vendor/dashboard', [vendorController::class, 'dashboard']);
    Route::post('vendor/addproduct', [vendorController::class, 'addproduct']);
    Route::get('vendor/viewproduct/{id}', [vendorController::class, 'viewproduct']);
    Route::get('vendor/viewproducts', [vendorController::class, 'viewproducts']);
    Route::put('vendor/updateproduct/{id}', [vendorController::class, 'updateproduct']);
    Route::put('vendor/freezeproduct/{id}', [vendorController::class, 'freezeproduct']);
    Route::put('vendor/unfreezeproduct/{id}', [vendorController::class, 'unfreezeproduct']);
    Route::delete('vendor/deleteproduct/{id}', [vendorController::class, 'deleteproduct']);
    Route::get('vendor/chat', [vendorController::class, 'chat']);
    Route::post('vendor/logout', [vendorController::class, 'vendorlogout']);
    //images 
});

//auth sanctum routes for users
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user/home', [buyerController::class, 'home']);
    Route::post('user/viewmoreinfo/{id}', [buyerController::class, 'viewmoreinfo']);
    Route::Post('users/chatvendor', [buyerController::class, 'chatvendor']);
    Route::post('user/logout', [buyerController::class, 'userlogout']);
});


//auth sanctum for admin
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('admin/dashboard', [adminController::class, 'dashboard']);
    Route::get('admin/viewvendors', [adminController::class, 'viewvendors']);
    Route::put('admin/vendor/approve/{id}', [adminController::class, 'approveVendor']); //option required-proof of business 
    Route::put('admin/vendor/blockvendor/{id}', [adminController::class, 'blockVendor']);
    Route::put('admin/vendor/unblockvendor/{id}', [adminController::class, 'unblockVendor']);
    Route::get('admin/users', [adminController::class, 'viewusers']);
    //admin to add categories 
});

// @desc Get all products
Route::get("/products-all", [buyerController::class, "getAllProducts"]);
// @desc Get product Ratings
Route::get("/product/{productId}/ratings", [buyerController::class, "getProductRatings"]);
// @desc Get User wishlist
Route::get("/user/product-wishlist", [buyerController::class, "getWishList"]);
// @desc Add Product to guest or users wishlist
Route::post("user/product/{productId}/add-wishlist", [buyerController::class, "addProductToWishlist"]);
// @desc Remove Product from guest or users wishlist
Route::delete("user/product/{productId}/remove-wishlist", [buyerController::class, "removeProductFromWishlist"]);


Route::post("/vendor/product/search-all", [vendorController::class, "searchByProduct"]);
Route::post("/vendor/product/search-category", [vendorController::class, "searchProductByCategory"]);
Route::get("/vendor/product/search-price", [vendorController::class, "searchProductByPriceRange"]);


Route::get("/product/preview-image", [vendorController::class, "previewProductImage"]);

//search option  
Route::get('/test/image-upload', function () {
    return view('productForm');
});

Route::get('/test/vendor/register', function () {
    return view('registerVendor');
});
