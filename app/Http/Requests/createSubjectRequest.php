<?php

namespace App\Http\Requests;

use App\Models\Subject;
use App\Models\Term;
use App\Rules\CreateClassroomRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateSubjectRequest extends FormRequest
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
        $term = Term::where('name', $this->input('term'))->pluck('id')->firstOrFail();
        return [
            'session' => ['required', 'exists:sessions,id'],
            'classroom' => ['required', 'exists:classrooms,id'],
            'term' => ['required', 'exists:terms,name'],
            'name' => ['required', 'string', function ($value, $attribute, $fail) use($term){
                $name = Subject::where('name', strtolower($attribute))
                    ->where('term_id', $term)
                    ->where('classroom_id', $this->input('classroom'))->first();
                if ($name) {
                    $fail("The selected subject already exists for the selected term and classroom");
                }
            }],
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
