<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:sessions,name'],
            'start_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'end_date' => ['required','date', 'date_format:Y-m-d', 'after:start_date'],
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'The :attribute field is required',
            '*.exists' => 'The selected :attribute does not exists',
            '*.date' => 'The selected :attribute must be a date',
            '*.unique' => 'The selected :attribute already exists.',
        ];
    }
}
