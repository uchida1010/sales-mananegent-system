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

        $roleId = $data['roleId'];

        $userData = [
            'user_code' => $data['userCode'],
            'name' => $data['name'],
            'name_kana' => $data['name_kana'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'position' => $data['position'],
            'joined_at' => $data['joined_at'],
        ];

        [$user, $token] = DB::transaction(function () use ($userData, $roleId) {
            $user = User::create($userData);

            $user->roles()->attach($roleId);

            $token = Password::broker()->createToken($user);

            return [$user, $token];
        });

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
