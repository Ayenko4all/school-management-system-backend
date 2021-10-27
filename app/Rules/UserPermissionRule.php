<?php

namespace App\Rules;

use App\Models\Permission;
use Illuminate\Contracts\Validation\Rule;

class UserPermissionRule implements Rule
{
    /**
     * @var string
     */
    private $columns;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($columns)
    {
        $this->columns = $columns;
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
        return Permission::whereIn('id', $this->columns)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'One of the selected :attribute does not exist.';
    }
}
