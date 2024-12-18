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

    public function shopvendor()
    {
        $this->belongsTo(shopvendor::class, 'shopvendor_id');
    }
}
