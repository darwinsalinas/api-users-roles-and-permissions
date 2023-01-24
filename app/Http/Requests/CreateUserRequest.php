<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\User;
use Illuminate\Http\Response;

class CreateUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'date_of_birth' => 'required|date|before_or_equal:' . $today,
            'gender' => 'required|in:male,female',
            'dni' => 'required|string|max:50',
            'address' => 'required|string|max:250',
            'country' => 'required|string|max:100',
            'phone_number' => 'required|string|max:20',
            'roles' => 'array|min:1|nullable',
            'roles.*' => 'required|string|max:255',
            'permissions' => 'array|min:1|nullable',
            'permissions.*' => 'required|string|max:255'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'message' => 'Validation Error',
                    'data' => $validator->errors()
                ],
                    Response::HTTP_BAD_REQUEST
            )
        );
    }
}
