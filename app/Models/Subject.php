<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Subject extends Model
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

    public function classrooms(){
        return $this->belongsToMany(Classroom::class, 'classroom_subject');
    }

    public function term(){
        return $this->belongsTo(Term::class);
    }

    public function session(){
        return $this->belongsTo(Session::class);
    }

    public function teachers(){
        return $this->belongsToMany(Classroom::class, 'subject_teacher');
    }
}
