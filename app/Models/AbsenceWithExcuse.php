<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenceWithExcuse extends Model
{
    protected $primaryKey = ['student_id', 'section_id','absence_date'];
    protected $fillable = [
        'student_id','section_id','absence_date','reason','file' 
     ];
    use HasFactory;
}
