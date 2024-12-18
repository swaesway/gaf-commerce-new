<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serviceinfo extends Model
{
    //
    use HasFactory;
    protected $table = 'Serviceinfos';
    protected $fillable = [
        'servicenumber',
        'telephone',
        'name'
    ];
}
