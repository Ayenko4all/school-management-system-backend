<?php

namespace App\Http\Requests;

use App\Rules\createSubjectRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateStudentRequest extends FormRequest
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

                'name'          => ['required', new CreateSubjectRule($this->input('name'), $this->input('classroom'))],
                'classroom'     => ['required', 'integer', 'exists:classrooms,id'],

        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'The :attribute field is required',
            '*.exists' => 'The selected :attribute does not exists',
        ];
    }
}
