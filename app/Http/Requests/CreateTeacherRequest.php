<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class CreateTeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return  $this->user()->can('create-teacher',  User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'martial_status'                => ['required', 'string'],
            'educational_level'             => ['required', 'string'],
            'tertiary_institution'          => ['nullable', 'string'],
            'country'                       => ['required', 'string'],
            'state'                         => ['required', 'string'],
            'lga'                           => ['required', 'string'],
            'address'                       => ['required', 'string'],
            'graduating_date'               => ['required', 'date'],
            'guarantor_one'                 => ['required', 'string'],
            'guarantor_two'                 => ['nullable', 'string'],
            'identity_card'                 => ['required', 'string'],
            'identity_card_photo'           => ['required', 'image'],
            'secondary_school'              => ['required', 'string'],
            'primary_school'                => ['required', 'string'],
            'photo'                         => ['required', 'image','max:4000'],
            'tertiary_certificate'          => ['nullable', 'file', Rule::requiredIf(function(){
                return $this->filled('tertiary_institution');
            })],
            'others_institution'            => ['nullable', Rule::requiredIf(function(){
                return $this->isNotFilled('tertiary_institution');
            })],
            'others_certificate'                  => ['nullable','file',  Rule::requiredIf(function(){
                return $this->filled('others_institution');
            })],
            'secondary_certificate'         => ['required', 'file',  Rule::requiredIf(function(){
                return $this->filled('secondary_school');
            })],
            'primary_certificate'           => ['required', 'file',  Rule::requiredIf(function(){
                return $this->filled('primary_school');
            })],
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'The :attribute field is required',
            '*.exists' => 'The :attribute field does not exist'
        ];
    }
}
