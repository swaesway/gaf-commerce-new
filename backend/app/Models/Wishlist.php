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

    function serviceinfo()
    {
        return $this->belongsTo(Serviceinfo::class, "servicenumber");
    }
}
