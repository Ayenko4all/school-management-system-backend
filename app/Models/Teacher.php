<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory;
    use softDeletes;

    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean'
    ];

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
