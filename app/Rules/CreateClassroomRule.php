<?php

namespace App\Rules;

use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Contracts\Validation\Rule;

class CreateClassroomRule implements Rule
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $session;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($name, $session)
    {
        $this->name = $name;
        $this->session = $session;
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
       return Classroom::where('name', $this->name)
            ->where('session_id',  $this->session)->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute already exists for the given session.';
    }
}
