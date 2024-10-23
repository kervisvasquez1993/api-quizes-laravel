<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question_title' => 'sometimes|string|max:255|unique:quizzes,question_title,' . $this->route('quiz'),
            'question_answer' => 'sometimes|boolean',
            'img' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            [
                'message' => 'Validation errors',
                'data' => $validator->errors()
            ]
        ));
    }
    protected function failedAuthorization()
    {

        throw new HttpResponseException(response()->json([
            'message' => 'You are not authorized to perform this action.',
        ], 403));
    }
}
