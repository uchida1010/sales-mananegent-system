<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /**
             * ユーザーコード
             *
             * システム内で一意となる識別子です。
             *
             * @example 11
             */
            'user_code' => (int)$this->user_code,

            /**
             * ユーザー名
             *
             * 画面上に表示する氏名です。
             *
             * @example 田中太郎
             */
            'name' => $this->name,

            /**
             * ユーザー名(かな)
             *
             * 画面上に表示する氏名のよみがなです。
             *
             * @example たなかたろう
             */
            'name_kana' => $this->name_kana,

            /**
             * メールアドレス
             *
             * ログインおよび通知に利用します。
             *
             * @example tanaka@example.com
             */
            'email' => $this->email,

            /**
             * 在籍状態
             *
             * 値の意味：
             * - active : 在職中
             * - leave : 休職中
             * - resigned : 退職済
             *
             * @example active
             */
            'status' => $this->status,

            /**
             * 保有権限一覧
             *
             * ユーザーに付与されている権限です。
             *
             * 値の例：
             * - admin
             * - office_worker
             * - sales
             * @var array<int, string>
             *
             * @example ["システム管理者", "事務", "営業"]
             */
            'roles' => $this->roles?->pluck('name')->toArray() ?? [],
        ];
    }
}
