<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student_Section extends Model
{
    protected $primaryKey = ['student_id', 'section_id'];
    protected $fillable = [
        'student_id','section_id','absence_percentage','number_of_absence' 
     ];
    use HasFactory;
}
