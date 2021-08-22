<?php

namespace App\Models;

use App\Http\Controllers\SchoolOwner\SchoolController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolOwner extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * {@inheritdoc}
     */
    //protected $guarded = [];
    protected $fillable = [
        'user_id',
        'school_id',
        'first_name',
        'last_name',
        'email',
        'telephone',
        'bvn',
        'id_card_type',
        'date_of_birth',
        'id_card_photo',
        'status',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = ['owners', 'array'];

    public function schools(){
        return $this->hasMany(School::class);
    }
}
