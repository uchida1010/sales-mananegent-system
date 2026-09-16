<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
             * 新規登録するユーザーのユーザーIDを指定してください。
             *
             * 入力例：
             * - 1
             * - 22
             *
             * @example 11
             */
            'userCode' => ['required', 'string'],

            /**
             * ユーザー名を指定します。
             *
             * 新規登録するユーザーのユーザー名を指定してください。
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
             * 新規登録するユーザーのユーザー名のよみがなを指定してください。
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
             * メールアドレスを指定します。
             *
             * 新規登録するユーザーのメールアドレスを指定してください。
             *
             * 入力例：
             * - tanaka@example.com
             * - yamada@example.com
             *
             * @example tanaka@example.com
             */
            'email' => ['required', 'string'],

            /**
             * 電話番号を指定します。
             *
             * 新規登録するユーザーの電話番号を指定してください。
             * 電話番号がない場合は省略できます。
             *
             * 入力例：
             * - 090-123-456
             *
             * @example 090-123-456
             */
            'phone' => ['nullable', 'string'],

            /**
             * パスワードを指定します。
             *
             * 新規登録するユーザーのログイン用パスワードを指定してください。
             * password_confirmation と同じ値を入力する必要があります。
             *
             * 入力例：
             * - Password123!
             *
             * @example Password123!
             */
            'password' => ['required', 'string', 'confirmed'],

            /**
             * 確認用パスワードを指定します。
             *
             * password と同じ値を指定してください。
             *
             * 入力例：
             * - Password123!
             *
             * @example Password123!
             */
            'password_confirmation' => ['required', 'string'],

            /**
             * 役職を指定します。
             *
             * 新規登録するユーザーの役職を指定してください。
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
             * 新規登録するユーザーの現在の雇用状態を指定してください。
             *
             * 指定例
             * - active: 在職
             * - leave: 休職
             * - resigned: 退職
             *
             * @example active
             */
            'status' => ['required', 'string', Rule::in(['active', 'leave', 'resigned'])],

            /**
             * 入社日を指定します。
             *
             * 新規登録するユーザーの入社日を指定してください。
             * YYYY-MM-DD形式で入力します。
             *
             * 入力例：
             * - 2026-04-01
             *
             * @example 2026-04-01
             */
            'joined_at' => ['required', 'date'],

            /**
             * 退職日を指定します。
             *
             * ユーザーが退職している場合に退職日を指定してください。
             * YYYY-MM-DD形式で入力します。
             * 退職していない場合は省略できます。
             *
             * 入力例：
             * - 2026-08-31
             *
             * @example 2026-08-31
             */
            'resigned_at' => ['nullable', 'date'],

            /**
             * 役割IDを指定します。
             *
             * 新規登録するユーザーの役割IDを指定してください。
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
            'roleId' => ['required', 'string'],
        ];
    }
}
