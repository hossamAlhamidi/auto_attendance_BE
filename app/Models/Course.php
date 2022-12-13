<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_id','course_name','abbreviation','course_hours','has_tutorial','has_lab' 
     ];
    use HasFactory;
}
