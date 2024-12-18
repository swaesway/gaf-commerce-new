<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ShopVendor extends Model
{   //
    use HasApiTokens;
    protected $table = 'shopvendors';
    protected $fillable = [
        'shopname',
        'email',
        'telephone',
        'location',
        'region',
        'approved' => 'boolean',
        'blockedstatus' => 'boolean',
        'password'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'shopvendor_id');
    }
}
