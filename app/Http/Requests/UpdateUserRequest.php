<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $today = date('Y-m-d');

        return [
            'name' => 'string|max:255|min:3',
            'email' => 'string|email|max:255|unique:users,email,' . $this->user->id,
            'password' => 'string|min:8',
            'date_of_birth' => 'date|before_or_equal:' . $today,
            'gender' => 'in:male,female',
            'dni' => 'string|max:50',
            'address' => 'string|max:250',
            'country' => 'string|max:100',
            'phone_number' => 'string|max:20',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation Error',
            'data' => $validator->errors()
        ], Response::HTTP_BAD_REQUEST));
    }
}
