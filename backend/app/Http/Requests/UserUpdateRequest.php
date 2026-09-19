<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
             * ユーザーIDを指定します。
             *
             * 更新するユーザーのユーザーIDを指定してください。
             * すでに登録されているユーザーIDは使用できません。
             * ただし更新するユーザー自身のユーザーIDは対象から外します。
             *
             * 入力例：
             * - 1
             * - 22
             *
             * @example 11
             */
            'userCode' => ['sometimes', 'required', 'string', Rule::unique('users', 'userCode')->ignore($this->route('user'))],

            /**
             * ユーザー名を指定します。
             *
             * 更新するユーザーのユーザー名を指定してください。
             *
             * 入力例：
             * - 田中 太郎
             * - 山田 花子
             *
             * @example 田中 太郎
             */
            'name' => ['sometimes', 'required', 'string'],

            /**
             * ユーザー名のよみがなを指定します。
             *
             * 更新するユーザーのユーザー名のよみがなを指定してください。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - たなか たろう
             * - やまだ はなこ
             *
             * @example たなか たろう
             */
            'name_kana' => ['sometimes', 'nullable', 'string'],

            /**
             * メールアドレスを指定します。
             *
             * 更新するユーザーのメールアドレスを指定してください。
             * すでに登録されているメールアドレスに更新できません。
             * ただし更新するユーザー自身のメールアドレスは対象から外します。
             * RFCに準拠したメールアドレス形式で、ドメインがDNS上に存在する必要があります。
             *
             * 入力例：
             * - tanaka@example.com
             * - yamada@example.com
             *
             * @example tanaka@example.com
             */
            'email' => ['sometimes', 'required', 'email:rfc,dns', Rule::unique('users', 'email')->ignore($this->route('user'))],

            /**
             * 電話番号を指定します。
             *
             * 更新するユーザーの電話番号を指定してください。
             * 最大20文字の登録ができます。
             * 電話番号がない場合は省略できます。
             *
             * 入力例：
             * - 090-123-456
             *
             * @example 090-123-456
             */
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],

            /**
             * 役職を指定します。
             *
             * 更新するユーザーの役職を指定してください。
             * 役職がない場合は省略できます。
             *
             * 入力例：
             * - 課長
             *
             * @example 課長
             */
            'position' => ['sometimes', 'nullable', 'string'],

            /**
             * 雇用状態を指定します。
             *
             * 更新するユーザーの現在の雇用状態を指定してください。
             *
             * 指定可能な値：
             * - active: 在職
             * - leave: 休職
             * - resigned: 退職
             *
             * @example active
             */
            'status' => ['sometimes', 'required', 'string', Rule::in(['active', 'leave', 'resigned'])],

            /**
             * 入社日を指定します。
             *
             * 更新するユーザーの入社日を指定してください。
             * YYYY-MM-DD形式で入力します。
             *
             * 入力例：
             * - 2026-04-01
             *
             * @example 2026-04-01
             */
            'joined_at' => ['sometimes', 'required', 'date'],

            /**
             * 退職日を指定します。
             *
             * 更新するユーザーの退職日を指定してください。
             * YYYY-MM-DD形式で入力します。
             * 退職していない場合はnullを指定できます。
             *
             * 入力例：
             * - 2026-09-30
             *
             * @example 2026-09-30
             */
            'resigned_at' => ['sometimes', 'nullable', 'date'],

            /**
             * 役割IDを指定します。
             *
             * 更新するユーザーの役割IDを指定してください。
             *
             * 役割IDは roles テーブルの主キーを指定してください。
             *
             * 指定例：
             * - 1 : システム管理者
             * - 2 : 事務担当
             * - 3 : 営業担当
             *
             *
             * @example 1
             */
            'roleId' => ['sometimes', 'required', 'string'],
        ];
    }
}
