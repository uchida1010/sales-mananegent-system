<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CustomerIndexRequest extends FormRequest
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
             * 取引先名または取引先名（よみがな）で検索します。
             *
             * 部分一致検索を行います。
             * 入力した文字列が取引先名または取引先のよみがなの住所に一部含まれる取引先を取得します。
             *
             * 検索例：
             * - "田中" → 「田中金属株式会社」「株式会社田中商事」
             * - "たなか" → 「たなかきんぞくかぶしきかいしゃ」
             *
             * @example　田中金属,たなかきんぞく
             */
            'keyword' => ['sometimes', 'string'],

            /**
             * 取引先IDで検索します。
             *
             * 完全一致検索を行います。
             * 入力した数字と一致する取引先IDを持つ取引先を取得します。
             *
             * 検索例：
             * - 1
             * - 123
             *
             * @example 123
             */
            'id' => ['sometimes', 'Integer'],

            /**
             * 住所で検索します。
             *
             * 部分一致検索を行います。
             * 入力した文字列が基本情報または配送情報の住所に一部含まれる取引先を取得します。
             *
             * 検索例：
             * - 大阪府大阪市大正区
             * - 東京都新宿区
             *
             * @example 大阪府大阪市大正区
             */
            'address' => ['sometimes', 'string'],

            /**
             * メールアドレスで検索します。
             *
             * 部分一致検索を行います。
             * 入力した文字列がメールアドレスの一部に含まれる担当者がいる取引先を取得します。
             *
             * 検索例：
             * - tanaka@example.com
             * - yamada@example.com
             *
             * @example tanaka@example.com
             */
            'email' => ['sometimes', 'string'],

            /**
             * TELまたはFAXで検索します。
             *
             * 部分一致検索を行います。
             * 入力した文字列がTELまたはFAXの一部に含まれる取引先を取得します。
             *
             * 検索例：
             * - 123-456-7890
             * - 1234567890
             *
             * @example　1234567890
             */
            'telFax' => ['sometimes', 'string'],

            /**
             * 担当者IDで検索します。
             *
             * 完全一致検索を行います。
             * 入力した担当者IDを持つ取引先のみ取得します。
             *
             * 担当者IDは users テーブルの主キーを指定してください。
             *
             * 指定例：
             * - 1 : 山田太郎
             * - 2 : 田中花子
             *
             * @example 1
             */
            'personInChargeId' => ['sometimes', 'string'],

            /**
             * リード獲得元IDで検索します。
             *
             * 完全一致検索を行います。
             * 入力したリード獲得元IDを持つ取引先のみ取得します。
             *
             * リード獲得元IDは lead_sources テーブルの主キーを指定してください。
             *
             * 指定例：
             * - 1 : HP
             * - 2 : 紹介
             *
             * @example 1
             */
            'leadSourceId' => ['sometimes', 'string'],

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
             * - /api/customer?page=1
             * - /api/customer?page=2
             *
             * @example 1
             */
            'page' => ['sometimes', 'string'],
        ];
    }
}
