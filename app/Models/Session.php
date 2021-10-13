<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Session extends Model
{
    use HasFactory;
    use softDeletes;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'boolean'
    ];

    public function classrooms(){
        return $this->hasMany(Classroom::class);
    }

    public function terms(){
        return $this->hasMany(Term::class);
    }
}
