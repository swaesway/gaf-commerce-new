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


    public function scopeAllProduct($query)
    {
        return $query
            ->with(['images', 'ratings']) // Eager load related data
            ->leftJoin('ratings', 'products.id', '=', 'ratings.product_id') // Join ratings table
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
            ) // Group by product ID for proper aggregation
            ->orderBy('average_rating', 'DESC'); // Order by average rating (descending)
    }


    public function scopeAllLatestProduct($query)
    {
        return $query
            ->with(['images', 'ratings']) // Eager load related data
            ->leftJoin('ratings', 'products.id', '=', 'ratings.product_id') // Join ratings table
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

    public function scopeSimilarProduct($query, $title, $description)
    {
        return $query
            ->with(["images", "ratings"])
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
            )
            ->where("title", "like", "%" . $title . "%")
            ->where("description", "like", "%" . $description . "%")
            ->where("price", ">", 0);
    }

    public function scopeSearchItem($query, string $value)
    {
        return $query->orWhere("title", "like", "%" . trim($value) . "%")
            ->orWhere("category", "like", "%" . trim($value) . "%")
            ->orWhere("description", "like", "%" . trim($value) . "%");
    }

    public function scopeSearchCategory($query, array $categories)
    {
        foreach ($categories as $category) {
            if ($category === "All") {
                return $query->where("price", ">", 0);
            }
        }

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

    public function scopeFilterByPriceAndCategory($query, array $prices = [], array $categories = [], $sortBy, $all_prices,  $all_categories)
    {

        $all_categories_value = [
            "All",
            "Uniforms",
            "Clothes",
            "Electronics",
            "Cosmetics",
            "Footwear",
            "Headgear",
            "Books and Stationary",
            "Food and Beverages"
        ];

        if ($sortBy === "Latest") {
            $query->with('ratings')
                ->leftJoin('ratings', 'products.id', '=', 'ratings.product_id')
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
                COALESCE(SUM(ratings.rating), 0) as total_rating,
                COALESCE(AVG(ratings.rating), 0) as average_rating
            ')
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
                )
                ->orderBy('created_at', 'DESC');
        }

        if ($sortBy === "Popularity") {
            $query->with('ratings')
                ->leftJoin('ratings', 'products.id', '=', 'ratings.product_id')
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
                COALESCE(SUM(ratings.rating), 0) as total_rating,
                COALESCE(AVG(ratings.rating), 0) as average_rating
            ')
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
                )
                ->orderBy('total_rating', 'DESC');
        }

        if ($sortBy === "Best Rating") {
            $query->with('ratings') // Eager load the ratings relationship
                ->leftJoin('ratings', 'products.id', '=', 'ratings.product_id') // Join ratings
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
                COALESCE(AVG(ratings.rating), 0) as average_rating
            ') // Calculate average rating
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
                )
                ->orderBy('average_rating', 'DESC');
        }


        if ($all_prices === "All" && $all_categories === "All") {
            return $query->where("price", ">", 0);
        }

        if ($all_prices === "All") {
            return $query->where("price", ">", 0)->whereIn("category", $categories);
        }



        if ($all_categories === "All") {
            $query->whereIn("category", $all_categories_value);

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

    public function totalProductRating()
    {
        return $this->ratings()->avg("rating");
    }
}
