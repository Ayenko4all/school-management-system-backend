<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\UserPermissionRule;
use Illuminate\Foundation\Http\FormRequest;

class UserPermissionRequest extends FormRequest
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
            'permissions'   => ['required', 'array', new UserPermissionRule($this->input('permissions'))],
        ];
    }
}
