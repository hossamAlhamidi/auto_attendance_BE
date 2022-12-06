<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $fillable = [
        'instructor_id','instructor_name','email','phone_number','password' 
     ];
    use HasFactory;
}
