<?php

namespace App\Http\Requests;

use App\Models\Session;
use App\Models\Term;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

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
        $session = Session::select(['start_date','end_date','name'])->where('id', $this->input('session'))->first();
        return [
            'name' => ['required', 'string', function ($value, $attribute, $fail) use ($session){
                $terms = Term::where('name', $attribute)
                ->where('session_id', $this->input('session'))->exists();
                if ($terms) {
                    $fail("The selected term already exists for " .strtolower($session->name));
                }
            }],
            'start_date' => ['required', 'date_format:Y-m-d', "after_or_equal:{$session->start_date}", 'before_or_equal:today'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after:start_date', "before_or_equal:{$session->end_date}", ''],
            'session' => ['required', 'exists:sessions,id']
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
