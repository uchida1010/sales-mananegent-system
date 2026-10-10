<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
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
             * 形式は「E + 3桁の数字」です。
             *
             * @example E011
             */
            'userCode' => $this->user_code,

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
            'nameKana' => $this->name_kana,

            /**
             * メールアドレス
             *
             * ログインおよび通知に利用します。
             *
             * @example tanaka@example.com
             */
            'email' => $this->email,

            /**
             * 電話番号
             *
             * 画面常に表示する電話番号です。
             *
             * @example 123-456-7890
             */
            'phone' => $this->phone,

            /**
             * 役職
             *
             * ユーザーに与えられている役職です。
             *
             * @example 123-456-7890
             */
            'position' => $this->position,

            /**
             * 在籍状態
             *
             * ユーザーの在籍状態を表しています。
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
             * 入社日
             *
             * ユーザーが入社する日または予定日です。
             *
             * @example 2026-04-01
             */
            'joined_at' => $this->joined_at?->format('Y-m-d'),

            /**
             * 退職日
             *
             *ユーザーが退職する日または予定日です。
             *
             * @example 2026-09-30
             */
            'resigned_at' => $this->resigned_at?->format('Y-m-d'),

            /**
             * 保有権限一覧
             *
             * ユーザーに付与されている権限です。
             *
             * 値の例：
             * - admin
             * - office_worker
             * - sales
             *
             * @var array<int, array{id: int, name: string}>
             *
             * @example [{"id": 1, "name": "システム管理者"}]
             */
            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                    ];
                })->toArray();
            }, []),
        ];
    }
}
