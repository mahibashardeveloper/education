<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    public function course_info(){
        return  $this->hasOne(Course::class, 'id', 'course_id');
    }

}
