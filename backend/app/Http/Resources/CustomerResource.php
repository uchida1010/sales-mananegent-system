<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
             * 取引先ID
             *
             * システム内で取引先を一意で識別する番号です。
             *
             * @example 1
             */
            'id' => $this->id,

            /**
             * 取引先名
             *
             * 画面上に表示する取引先名です。
             *
             * @example ABC株式会社
             */
            'name' => $this->name,

            /**
             * 住所
             *
             * 画面上に表示する取引先の住所です。
             *
             * @example 大阪府大阪市
             */
            'address' => $this->address,

            /**
             * 担当者
             *
             * 画面上に表示する担当者です。
             * 取引先は誰が担当するのかを管理します。
             *
             * @var array{id: int|null, name: string|null}
             *
             * @example {"id": 1, "name": "山田太郎"}
             */
            'person_in_charge' => $this->whenLoaded('personInCharge', function () {
                return [
                    'id' => $this->personInCharge?->id,
                    'name' => $this->personInCharge?->name,
                ];
            }),

            /**
             * リード獲得元
             *
             * 画面上に表示するリード獲得元
             * 取引先になった経路やきっかけを管理します。
             *
             * 値の例：
             * - HP
             * - 営業
             * - 紹介
             *
             * @var array{id: int, name: string}
             *
             * @example {"id": 1, "name": "HP"}
             */
            'lead_source' => $this->whenLoaded('leadSource', function () {
                return [
                    'id' => $this->leadSource?->id,
                    'name' => $this->leadSource?->name,
                ];
            }),
        ];
    }
}
