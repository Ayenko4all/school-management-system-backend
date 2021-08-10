<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LgaArea extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lga',
        'state_id',
        'status',
    ];

    protected $dates = ['deleted_at'];

    public function stateType(){
        return $this->belongsTo(State::class,'state_id');
    }
}
