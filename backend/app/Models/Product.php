<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function shopvendor()
    {
        $this->belongsTo(shopvendor::class, 'shopvendor_id');
    }
}
