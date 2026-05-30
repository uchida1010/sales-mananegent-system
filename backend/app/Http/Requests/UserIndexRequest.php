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

            /**
             * 名前、よみがな
             */
            'keyword' => ['sometimes', 'string'],
            'userCode' => ['sometimes', 'string'],

            /**
             * メールアドレス
             * @example abcd@example.com
             */
            'email' => ['sometimes', 'string'],

            /**
             * trueの場合、在職者のみ取得する
             */
            'activeOnly' => ['sometimes', 'string'],

            /**
             * 役職者
             * @example　システム管理者、事務
             */
            'role' => ['sometimes', 'string'],
        ];
    }
}
