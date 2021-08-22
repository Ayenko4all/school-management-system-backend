<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory, SoftDeletes;

    public $guard_name = '*';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
        'condition',
        'status'
    ];

    protected $dates = ['deleted_at'];

    public function schools(){
        return $this->belongsToMany(School::class, 'school_modules')
            ->withTimestamps();
           // ->select(['modules.id','modules.name','modules.price','modules.condition']);
    }

}
