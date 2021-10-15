<?php

namespace App\Http\Requests;

use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', Session::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string',Rule::unique('sessions')->ignore($this->route('session'))],
            'start_date' => ['required', 'date', 'date_format:Y-m-d'],
            'end_date' => ['required','date', 'date_format:Y-m-d', 'after:start_date'],
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'The :attribute field is required',
            '*.unique' => 'The :attribute already exists',
            '*.exists' => 'The selected :attribute does not exists',
            '*.date' => 'The selected :attribute must be a date',
        ];
    }
}
