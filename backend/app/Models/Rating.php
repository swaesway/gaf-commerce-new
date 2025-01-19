<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = "ratings";

    protected $fillable = [
        "rating",
        "comment"
    ];

    protected $hidden = [
        "servicenumber",
        "product_id",
        "updated_at"
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function serviceinfos()
    {
        return $this->hasOne(Serviceinfo::class, "id", "servicenumber");
    }
}
