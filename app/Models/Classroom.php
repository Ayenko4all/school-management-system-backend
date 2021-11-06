<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Classroom.
 *
 * @property int                                                               $id
 * @property string                                                            $name
 * @property boolean                                                           $status
 * @property null|\Illuminate\Support\Carbon                                   $created_at
 * @property null|\Illuminate\Support\Carbon                                   $updated_at
 * @property null|\Illuminate\Support\Carbon                                   $deleted_at
 * @property \App\Models\Subject                                               $subjects
 * @property \App\Models\Session                                               $session
 * @property \App\Models\Teacher                                               $teachers
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Classroom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Classroom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Classroom query()
 * @method static \Illuminate\Database\Eloquent\Builder|Classroom whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classroom whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classroom whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classroom whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Classroom whereDeletedAt($value)
 *
 * * @mixin \Eloquent
 */

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
        return $this->belongsToMany(Subject::class, 'classroom_subject');
    }

    public function teachers(){
        return $this->belongsToMany(Teacher::class, 'classroom_teacher');
    }

    public function session(){
        return $this->hasOne(Session::class, 'id', 'session_id');
    }
}
