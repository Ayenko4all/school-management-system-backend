<?php

namespace App\Http\Requests;

use App\Enums\VerificationEnum;
use Illuminate\Foundation\Http\FormRequest;

class VerifyPasswordResetRequest extends FormRequest
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
        return [
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'token'     =>  ['required', 'digits:6', 'exists:tokens,token'],
            'type'      => ['required','string',
                function ($attribute, $value, $fail) {
                    if ($value !== VerificationEnum::PASSWORD) {
                        $fail($attribute.' is not valid.');
                    }
                },]
        ];
    }
}
