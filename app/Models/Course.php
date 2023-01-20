<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Course extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'course_id';
    protected $fillable = [
        'course_id','course_name','abbreviation','course_hours','has_tutorial','has_lab' 
     ];
}
