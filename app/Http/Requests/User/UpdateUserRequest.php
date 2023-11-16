<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'name'=>['required'],
            'email'=>['required'],
            'current_password'=>'nullable|required_with:new_password|current_password',
            'new_password'=>'required_with:current_password|min:8|nullable',
            'confirm_password'=>'required_with:new_password|same:new_password',
            'status'=>['nullable','between:0,1'],
            'roles'=>['required']

        ];
    }
}
