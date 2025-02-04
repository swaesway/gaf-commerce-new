<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Callback extends Model
{

    protected $table = "callbacks";

    protected $fillable = ["status"];


    public function scopeMyCallbacks($query)
    {
        return $query
            ->with(["images"])
            ->leftJoin("serviceinfos", "callbacks.servicenumber", "=", "serviceinfos.id")
            ->leftJoin("products", "callbacks.product_id", "=", "products.id")
            ->leftJoin("ratings", "callbacks.product_id", "=", "ratings.product_id")
            ->select([
                "serviceinfos.name as service_name",
                "serviceinfos.email as service_email",
                "serviceinfos.telephone as service_telephone",
                "callbacks.id as callback_id",
                "callbacks.status as callback_status",
                "callbacks.view as callback_view",
                "callbacks.created_at as callback_created_at",
                "products.id",
                "products.title",
                "products.shopvendor_id",
                "products.price",
                "products.category",
                "products.description",
                DB::raw("COALESCE(AVG(ratings.rating), 0) as average_rating")
            ])
            ->groupBy(
                "serviceinfos.name",
                "serviceinfos.email",
                "serviceinfos.telephone",
                "callbacks.id",
                "callbacks.created_at",
                "callbacks.status",
                "callbacks.view",
                "products.id",
                "products.title",
                "products.shopvendor_id",
                "products.price",
                "products.category",
                "products.description"
            );
    }


    public function images()
    {
        return $this->hasMany(ProductImage::class, "product_id");
    }
}
