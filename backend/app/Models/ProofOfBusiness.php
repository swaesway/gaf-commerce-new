<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProofOfBusiness extends Model
{
    function shopvendor()
    {
        return $this->belongsTo(ShopVendor::class);
    }
}
