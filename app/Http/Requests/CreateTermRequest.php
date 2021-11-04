<?php

namespace App\Http\Requests;

use App\Models\Session;
use App\Models\Term;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CreateTermRequest extends FormRequest
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
        $session = Session::select(['start_date','end_date','name','id'])->where('id', $this->input('session'))->first();
        if (! $session){
            throw ValidationException::withMessages(['session' => 'selected session does not exist.']);
        } else {
            $rules = [
                'name' => ['required', 'string', 'exists:terms,name', function ($value, $attribute, $fail) use ($session){
                    $terms = Term::where('name', $attribute)
                        ->whereRelation('sessions', 'session_id', $this->input('session'))->exists();
                    if ($terms) {
                        $fail("The selected term already exists for " .strtolower($session->name));
                    }
                }],
                'start_date' => ['required', 'date_format:Y-m-d', "after_or_equal:{$session->start_date}"],
                'end_date' => ['required', 'date_format:Y-m-d', 'after:start_date', "before_or_equal:{$session->end_date}"],
            ];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            '*.required' => 'The :attribute field is required',
            '*.exists' => 'The selected :attribute does not exist'
        ];
    }
}
