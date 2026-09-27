<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\InitialPasswordSetupNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class UserService
{
    /**
     * @param array{
     *     userCode: string,
     *     name: string,
     *     name_kana?: string|null,
     *     email: string,
     *     phone: string,
     *     position?: string|null,
     *     joined_at: string,
     *     roleId: string
     * } $data
     */
    public function create(array $data)
    {
        $data['user_code'] = $data['userCode'];
        unset($data['userCode']);

        $roleId = $data['roleId'];
        unset($data['roleId']);

        $user = DB::transaction(function () use ($data, $roleId) {
            $user = User::create($data);

            $user->roles()->attach($roleId);

            return $user;
        });

        $token = Password::broker()->createToken($user);

        $user->notify(
            new InitialPasswordSetupNotification($token)
        );

        return $user;
    }

    public function setupPassword(array $data): void
    {
        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->update([
                    'password' => $password,
                ]);
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'token' => ['初期パスワード設定用のトークンが無効です。'],
            ]);
        }
    }
}
