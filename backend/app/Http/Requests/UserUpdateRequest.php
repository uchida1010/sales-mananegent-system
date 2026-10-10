<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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
             * ユーザーコードを指定します。
             *
             * 更新するユーザーのユーザーコードを指定してください。
             * 「E + 3桁の数字」で指定します。
             * すでに登録されているユーザーコードは使用できません。
             * ただし更新するユーザー自身のユーザーコードは対象から外します。
             *
             * 入力例：
             * - E001
             * - E022
             *
             * @example E011
             */
            'userCode' => ['required', 'string', Rule::unique('users', 'user_code')->ignore($this->route('user')), 'regex:/^E\d{3}$/'],

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
            'name' => ['required', 'string'],

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
            'name_kana' => ['nullable', 'string'],

            /**
             * パスワードを指定します。
             *
             * 更新するユーザーのパスワードを指定してください。
             * 必要がない場合は省略できます。
             * 8文字以上で入力してください。
             *
             * @example Password123!
             */
            'password' => ['nullable', 'confirmed', Password::min(8), 'string'],

            /**
             * 確認用パスワードを指定します。
             *
             * パスワードの入力内容を確認するための項目です。
             * パスワードを変更する場合は必須です。
             *
             * @example Password123!
             */
            'password_confirmation' => ['required_with:password', 'nullable', 'string'],

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
            'email' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->ignore($this->route('user'))],

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
            'phone' => ['nullable', 'string', 'max:20'],

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
            'position' => ['nullable', 'string'],

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
             * @format string
             *
             * @example active
             */
            'status' => ['required', 'string', Rule::in(['active', 'leave', 'resigned'])],

            /**
             * 入社日を指定します。
             *
             * 更新するユーザーの入社日を指定してください。
             * YYYY-MM-DD形式で入力します。
             *
             * 入力例：
             * - 2026-04-01
             *
             * @format date
             *
             * @example 2026-04-01
             */
            'joined_at' => ['required', 'date'],

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
             * @format date
             *
             * @example 2026-09-30
             */
            'resigned_at' => ['nullable', 'date'],

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
             * @example 1
             */
            'roleId' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => ':attributeは必須です。',
            '*.regex' => ':attributeを正しい形式で入力してください。',
            '*.string' => ':attributeは文字列で入力してください。',
            '*.unique' => ':attributeはすでに使用されています。',

            'email.email' => '正しいメールアドレスを入力してください。',
            'phone.max' => '電話番号は20文字以内で入力してください。',
            'joined_at.date_format' => '入社日はYYYY-MM-DD形式で入力してください。',
            'roleId.exists' => '選択された権限は存在しません。',

            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.confirmed' => 'パスワードと確認用パスワードが一致しません。',
            'password_confirmation.required_with' => '確認用パスワードを入力してください。',
        ];
    }

    public function attributes(): array
    {
        return [
            'userCode' => 'ユーザーコード',
            'name' => '氏名',
            'name_kana' => 'よみがな',
            'email' => 'メールアドレス',
            'password' => 'パスワード',
            'password_confirmation' => 'パスワード確認用',
            'phone' => '電話番号',
            'position' => '役職',
            'status' => '状態',
            'joined_at' => '入社日',
            'resigned_at' => '退職日',
            'roleId' => '権限',
        ];
    }
}
