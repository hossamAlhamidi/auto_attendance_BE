<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = [
       'admin_id','admin_name','email','phone_number','password' 
    ];
    use HasFactory;
}
