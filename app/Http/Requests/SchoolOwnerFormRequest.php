<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolOwnerFormRequest extends FormRequest
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
            'school_id'     => ['required', 'exists:schools,id'],
            'first_name'    => ['required', 'string'],
            'last_name'     => ['required', 'string'],
            'email'         => ['required', 'email','unique:directors,email'],
            'telephone'     => ['required', 'numeric','unique:directors,telephone'],
            'bvn'           => ['required', 'numeric', 'digits:11'],
            'id_card_type'  => ['required', 'string'],
            'date_of_birth' => ['required', 'string', 'date'],
            'id_card_photo' => ['required', 'file']
        ];

        if($this->request->get('owners')){
            foreach($this->request->get('owners') as $key => $val)
            {
                $rules['owners.'.$key.'.first_name'] = 'required';
                $rules['owners.'.$key.'.last_name'] = 'required';
                $rules['owners.'.$key.'.email'] = 'required';
                $rules['owners.'.$key.'.school_id'] = 'required';
            }
        }

        return $rules;
//        return [
//            'user_id'       => ['required', 'exists:users,id'],
//            'school_id'     => ['required'],
//            'first_name'    => ['required', 'string'],
//            'last_name'     => ['required', 'string'],
//            'email'         => ['required', 'email', 'unique:owner_directors,email'],
//            'telephone'     => ['required', 'numeric'],
//            'bvn'           => ['required', 'numeric', 'digits:11'],
//            'id_card_type'  => ['required', 'string'],
//            'date_of_birth' => ['required', 'string', 'date'],
//            'id_card_photo' => ['required', 'file']
//        ];
    }

}
