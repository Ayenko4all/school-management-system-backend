<?php

namespace App\Http\Requests;

use App\Models\Subject;
use App\Models\Term;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateSubjectRequest extends FormRequest
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
    public function rules(Subject $subject)
    {
        $term = Term::where('id', $this->input('term'))->pluck('id')->first();
        if(! $term){
            throw ValidationException::withMessages(['term' => 'selected term does not exist.']);
        } else {
            return [
                'session' => ['required', 'exists:sessions,id'],
                'term' => ['required', 'exists:terms,id'],
                'status'  => ['required', 'boolean'],
                'classroom' => ['required','exists:classrooms,id'],
                'name'  => ['required', 'string',
                    function($value, $attribute, $fail) use($term, $subject){
                        $name = Subject::where('name', strtolower($attribute))
                            ->where('term_id', $term)
                            ->whereRelation('classrooms', 'classroom_id', $this->input('classroom'))->first();
                        if ($name) {
                            $fail("The selected subject already exists for the selected term and classroom");
                        }
                    }],
            ];
        }

    }

    public function messages()
    {
        return [
            '*.required' => 'The :attribute field is required',
            '*.unique' => 'The :attribute already exists',
            '*.exists' => 'The selected :attribute does not exists',
        ];
    }
}
