<?php

namespace App\Http\Requests;

use App\Enums\VerificationEnum;
use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailRequest extends FormRequest
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
            'email'     => ['required','email','exists:tokens,email'],
            'token'     => ['required', 'digits:6', 'exists:tokens,token']
        ];
    }
}
