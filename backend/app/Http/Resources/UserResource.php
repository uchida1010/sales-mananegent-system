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
            'id' => $this->id,
            'user_code' => $this->user_code,
            'name' => $this->name,
            'name_kana' => $this->name_kana,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'joined_at' => $this->joined_at,
            'resigned_at' => $this->resigned_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
