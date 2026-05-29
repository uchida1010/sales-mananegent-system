<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserIndexRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string'],
            'userCode' => ['nullable', 'string'],
            'email' => ['nullable', 'string'],
            'activeOnly' => ['nullable', 'in:true,false,1,0'],
            'role' => ['nullable', 'string'],
        ];
    }
}
