<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = 'products';
    protected $fillable = [
        'vendorid',
        'productid',
        'title',
        'category',
        'price',
        'description',
        'frozen' => 'boolean'
    ];

    protected $hidden = [
        "productid",
        "frozen",
        "created_at",
        "updated_at"
    ];

    public function scopeWishListProduct($query,  $wishList)
    {

        foreach ($wishList as $wishlist) {
            $query->orWhere("id", $wishlist["product_id"]);
        }

        return $query;
    }

    public function shopvendor()
    {
        $this->belongsTo(shopvendor::class, 'shopvendor_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, "product_id");
    }
}
