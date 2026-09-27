<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordSetupRequest extends FormRequest
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

            /**
             * 初期パスワード設定用のトークンを指定します。
             *
             * ユーザー登録時に発行された初期パスワード設定用の
             * トークンを指定してください。
             *
             * @example abcdef1234567890
             */
            'token' => ['required', 'string'],

            /**
             * メールアドレスを指定します。
             *
             * 初期パスワードを設定するユーザーの
             * メールアドレスを指定してください。
             *
             * @example tanaka@example.com
             */
            'email' => ['required', 'email:rfc,dns', 'exists:users,email'],

            /**
             * パスワードを指定します。
             *
             * 初期パスワードを設定するユーザーの
             * パスワードを指定してください。
             *
             * @example Password123!
             */
            'password' => ['required', 'confirmed', Password::min(8)],

            /**
             * 確認用パスワードを指定します。
             *
             * passwordと同じ値を指定してください。
             *
             * @example Password123!
             */
            'password_confirmation' => ['required', 'string'],
        ];
    }
}
