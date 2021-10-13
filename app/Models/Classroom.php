<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Classroom extends Model
{
    use HasFactory;
    use softDeletes;
    use HasSlug;


    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return generateSlugName();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function subjects(){
        return $this->belongsToMany(Subject::class , 'subject_teacher');
    }

    public function teachers(){
        return $this->belongsToMany(Teacher::class, 'classroom_teacher');
    }

    public function session(){
        return $this->hasOne(Session::class, 'id', 'session_id');
    }
}
