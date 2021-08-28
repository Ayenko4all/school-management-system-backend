<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'    => ['required', 'string'],
            'last_name'     => ['required', 'string'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'telephone'     => ['required', 'string', 'numeric','unique:users'],
            'gender'      => ['required','string',
                function ($attribute, $value, $fail) {
                    if (filled($value)) {
                        $Types = collect(['male','female']);
                        if(! $Types->contains($value)){
                            $fail('The selected '. $attribute.' is not valid.');
                        }
                    }
                },],
        ];
    }
}
