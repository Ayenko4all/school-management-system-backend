<?php

namespace App\Rules;

use App\Models\Subject;
use Illuminate\Contracts\Validation\Rule;

class createSubjectRule implements Rule
{
    /**
     * @var string
     */
    private $name;
    private $classroom;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($name, $classroom)
    {
        $this->name = $name;
        $this->classroom = $classroom;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //dd($this->classroom);
       return Subject::where('name', $this->name)
            ->where('classroom_id',  $this->classroom)->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute already exists for the given classroom.';
    }
}
