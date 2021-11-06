<?php

namespace App\Http\Requests;

use App\Rules\CreateClassroomRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateClassroomRequest extends FormRequest
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
            'name'    => ['required', new CreateClassroomRule($this->input('name'), $this->input('session'))],
            'session' => ['required', 'exists:sessions,id'],
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'The :attribute field is required',
            '*.exists' => 'The selected :attribute does not exist'
        ];
    }
}
