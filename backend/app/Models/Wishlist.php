<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlists';

    protected $fillable = [
        "servicenumber",
        "product_id"
    ];

    public function scopeWishlistProduct($query)
    {
        return $query->with(['product']) // Eager load related data
            ->leftJoin('products', 'wishlists.product_id', '=', 'products.id') // Join ratings table
            ->leftJoin("ratings", "products.id", "=", "ratings.product_id")
            ->selectRaw('
            products.id,
            products.title,
            products.shopvendor_id,
            products.price,
            products.category,
            products.description,
            products.frozen,
            products.created_at,
            products.updated_at,
            COALESCE(AVG(ratings.rating), 0) as average_rating') // Select all product fields and calculate the average rating
            ->groupBy(
                'products.id',
                'products.title',
                'products.shopvendor_id',
                'products.price',
                'products.category',
                'products.description',
                'products.frozen',
                'products.created_at',
                'products.updated_at'
            );
    }


    function product()
    {
        return $this->belongsTo(Product::class, "id")->with(["images", "ratings"]);
    }

    function serviceinfo()
    {
        return $this->belongsTo(Serviceinfo::class, "servicenumber");
    }
}
