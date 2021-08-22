<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    protected $dates = ['deleted_at'];

    public function directors(){
        return $this->hasMany(SchoolOwner::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function school_type(){
        return $this->belongsTo(SchoolType::class);
    }

    public function modules(){
        return $this->belongsToMany(Module::class, 'school_modules')
            ->withTimestamps();
            //->select(['modules.id','modules.name','modules.price','modules.condition']);
    }

}
