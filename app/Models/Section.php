<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $primaryKey = ['course_id', 'instructor_id'];
    public $incrementing = false;
    protected $fillable = [
        'section_id','course_id','course_name','instructor_id','instructor_name','classroom','time','type'
     ];
     protected $casts = [
        'time'=>'array'
     ];
}
