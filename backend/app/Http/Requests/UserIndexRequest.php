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
             * ユーザー名またはユーザー名（かな）で検索します。
             *
             * 部分一致検索を行います。
             * 名前・よみがなのいずれかに一致するユーザーを取得します。
             *
             * 検索例：
             * - "田中" → 「田中太郎」「田中一郎」
             * - "たなか" → 「たなかたろう」
             * - "太郎" → 「山田太郎」
             *
             * @example 田中,たなか
             */
            'keyword' => ['sometimes', 'string'],

            /**
             * ユーザーIDで検索します。
             *
             * 完全一致検索を行います。
             * ユーザーIDを指定してください。
             *
             * 検索例：
             * - 1
             * - 22
             *
             * @example 11
             */
            'userCode' => ['sometimes', 'string'],

            /**
             * メールアドレスで検索します。
             *
             * 完全一致検索を行います。
             * 指定したメールアドレスを持つユーザーを取得します。
             *
             * 検索例：
             * - tanaka@example.com
             * - yamada@example.com
             *
             * @example tanaka@example.com
             */
            'email' => ['sometimes', 'string'],

            /**
             * 在職者のみを取得するかを指定します。
             *
             * true を指定した場合は在職中(status:active)のユーザーのみ取得します。
             * false の場合は退職者、休職者を含む全ユーザーを取得します。
             *
             * API仕様上は文字列として受け取ります。
             *
             * 指定例：
             * - true
             * - false
             *
             * @example true
             */
            'activeOnly' => ['sometimes', 'string'],

            /**
             * 役割IDで検索します。
             *
             * 完全一致検索を行います。
             * 指定した役割IDを持つユーザーのみ取得します。
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
            'roleId' => ['sometimes', 'string'],

            /**
             * ページ番号を指定します。
             *
             * ページネーションされた一覧データの取得対象ページを指定します。
             * 指定しない場合は1ページ目を取得します。
             *
             * 1ページあたりの取得件数はサーバー側で設定された件数に従います。
             *
             * 指定例：
             * - 1 : 1ページ目を取得
             * - 2 : 2ページ目を取得
             * - 3 : 3ページ目を取得
             *
             * リクエスト例：
             * - /api/user?page=1
             * - /api/user?page=2
             *
             * @example 1
             */
            'page' => ['sometimes', 'string'],
        ];
    }
}
