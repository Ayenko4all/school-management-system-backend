<?php

namespace App\Http\Requests;

use App\Models\Session;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTermRequest extends FormRequest
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
        $session = Session::select(['start_date','end_date','name'])->where('id', $this->input('session'))->first();
        return [
            'name' => ['required', 'string', 'exists:terms,name',Rule::unique('terms')->ignore($this->route('term'))],
            'start_date' => ['required', 'date_format:Y-m-d', "after_or_equal:{$session->start_date}"],
            'end_date' => ['required', 'date_format:Y-m-d', 'after:start_date', "before_or_equal:{$session->end_date}"],
            'session' => ['required', 'exists:sessions,id']
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
