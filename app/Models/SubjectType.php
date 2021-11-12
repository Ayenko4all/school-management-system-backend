<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\SubjectType.
 *
 * @property int                                                               $id
 * @property string                                                            $name
 * @property boolean                                                           $status
 * @property null|\Illuminate\Support\Carbon                                   $created_at
 * @property null|\Illuminate\Support\Carbon                                   $updated_at
 * @property null|\Illuminate\Support\Carbon                                   $deleted_at
 * @property \App\Models\Subject                                               $subjects
 * @property \App\Models\Session                                               $session
 * @property \App\Models\Term                                                  $terms

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

class SubjectType extends Model
{
    use HasFactory;


    protected $guarded = [];

    public function subject(){
        return $this->hasMany(Subject::class, 'subject_type_id');
    }
}
