<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class CreateGuestBookRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'captcha_answer' => 'required|string',
            'name' => 'required|string|regex:/^[\p{Cyrillic}A-Za-z\s]{2,}$/u',
            'review' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Поле :attribute обязательно для заполнения',
            'regex' => 'Поле :attribute должно содержать как минимум две буквы и может включать в себя только буквы и пробелы.',
        ];
    }

    public function attributes(): array
    {
        return [
            'captcha_answer' => 'Введите код с картинки',
            'name' => 'Ваше имя',
            'review' => 'Введите отзыв'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        Log::error('Произошла ошибка: ', $errors->getMessages());

        throw new HttpResponseException(
            response()->json([
                'message' => $errors->all()
            ], 422)
        );
    }
}
