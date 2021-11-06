<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Classroom.
 *
 * @property int                                                               $id
 * @property string                                                            $name
 * @property boolean                                                           $status
 * @property string                                                            $duration
 * @property null|\Illuminate\Support\Carbon                                   $start_at
 * @property null|\Illuminate\Support\Carbon                                   $end_at
 * @property null|\Illuminate\Support\Carbon                                   $created_at
 * @property null|\Illuminate\Support\Carbon                                   $updated_at
 * @property null|\Illuminate\Support\Carbon                                   $deleted_at
 * @property \App\Models\Term                                                  $terms
 * @property \App\Models\Classroom                                             $classrooms
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
        return $this->belongsToMany(Term::class);
    }
}
