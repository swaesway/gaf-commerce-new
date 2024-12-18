<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class loginModel extends Model
{
    //
    protected $table = 'loginmodel';
    protected $fillable = [
        'servicenumber',
        'token'
    ];
}
