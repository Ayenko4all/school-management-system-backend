<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function classrooms(){
        return $this->belongsToMany(Classroom::class, 'classroom_teacher');
    }

    public function students(){
        return $this->belongsToMany(Student::class, 'student_teacher');
    }

    public function subjects(){
        return $this->belongsToMany(Subject::class, 'subject_teacher');
    }
}
