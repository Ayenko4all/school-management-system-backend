<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolSetUpFormRequest extends FormRequest
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
        $rules = [
            'school_name'               => ['required', 'string'],
            'school_address'            => ['required', 'string'],
            'bvn'                       => ['required', 'numeric', 'digits:11'],
            'city'                      => ['required', 'string'],
            'lga'                       => ['required', 'string'],
            'state'                     => ['required', 'string'],
            'school_email_address'      => ['required', 'string', 'unique:schools,school_email_address'],
            'school_telephone_address'  => ['required', 'string', 'unique:schools,school_telephone_address'],
            'school_type_id'               => ['required', 'exists:school_types,id'],
            'cac_document'              => ['nullable', 'file'],
        ];


        return $rules;

    }


}
