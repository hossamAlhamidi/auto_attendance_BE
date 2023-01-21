<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Instructor extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'instructor_id';
    
    protected $fillable = [
        'instructor_id','instructor_name','email','is_admin','phone_number','password' 
     ];
     protected $casts = [
        'instructor_id' => 'string',
    ];
}
