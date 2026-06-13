<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
             * 役割ID
             *
             * roles テーブルの主キーです。
             * 役割を一意に識別するためのIDです。
             *
             * 指定例：
             * - 1 : システム管理者
             * - 2 : 事務
             * - 3 : 営業
             *
             * @example 1
             */
            'id' => $this->id,

            /**
             * 役割名
             *
             * 利用可能な役割の名称です。
             * 検索画面のユーザー情報の表示などに利用します。
             *
             * 値の例：
             * - システム管理者
             * - 事務
             * - 営業
             *
             * @example システム管理者
             */
            'name' => $this->name,
        ];
    }
}
