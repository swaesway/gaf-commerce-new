<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\isArray;

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
        "updated_at"
    ];


    public function scopeSearchItem($query, string $value)
    {
        return $query->orWhere("title", "like", "%" . trim($value) . "%")
            ->orWhere("category", "like", "%" . trim($value) . "%")
            ->orWhere("description", "like", "%" . trim($value) . "%");
    }

    public function scopeSearchCategory($query, array $categories)
    {

        return $query->whereIn("category", $categories);
    }

    public function scopeSearchPrice($query, array $prices, string $allPrices = "")
    {

        if ($allPrices === "all") {
            return $query->where("price", ">", 0);
        }

        foreach ($prices as $price) {
            $range = explode("-", $price);

            if (count($range) === 2) {
                $query->orWhereBetween("price", [$range[0], $range[1]]);
            }
        }

        return $query;
    }

    public function scopeFilterByPriceAndCategory($query, array $prices = [], array $categories = [])
    {


        foreach ($prices as $price) {
            if ($price === "All") {
                return $query->where("price", ">", 0);
            }
        }

        foreach ($categories as $category) {
            if ($category === "All") {
                return $query->where("price", ">", 0);
            }
        }

        if (!empty($categories)) {
            $query->whereIn('category', $categories);
        }

        if (!empty($prices)) {
            $query->where(function ($subQuery) use ($prices) {
                foreach ($prices as $price) {
                    $range = explode('-', $price);

                    if (count($range) === 2) {
                        $subQuery->orWhereBetween('price', [(float)$range[0], (float)$range[1]]);
                    }
                }
            });
        }

        return $query;
    }



    public function scopeWishListProduct($query,  $wishList)
    {

        foreach ($wishList as $wishlist) {
            $query->orWhere("id", $wishlist["product_id"]);
        }

        return $query;
    }


    // public function scopeFilterProduct($query, array $prices, array $categories)
    // {
    //     if (is_array($prices) || is_array($categories)) {
    //         $query->whereIn('price', $prices)
    //             ->whereIn('category', $categories);
    //     }
    // }

    public function shopvendor()
    {
        return $this->belongsTo(shopvendor::class, 'shopvendor_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, "product_id");
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, "product_id");
    }
}
