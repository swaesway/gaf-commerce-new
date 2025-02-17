<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serviceinfo extends Model
{
    //
    use HasFactory;
    protected $table = 'serviceinfos';
    protected $fillable = [
        'servicenumber',
        'telephone',
        'name'
    ];

    protected $hidden = [
        "servicenumber",
        "telephone",
        "created_at",
        "updated_at",

    ];


    public function wishlist()
    {
        return $this->hasMany(Wishlist::class, "servicenumber");
    }
}
