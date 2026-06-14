<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ユーザー一覧APIが、ユーザーのリソース形状を期待通りに返すこと
     */
    public function test_index_returns_users_with_expected_resource_shape(): void
    {
        $adminRole = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $officeWorkerRole = Role::create([
            'name' => '事務',
            'code' => 'office_worker',
        ]);

        $admin = User::factory()->create([
            'user_code' => '1001',
            'name' => '田中太郎',
            'name_kana' => 'たなかたろう',
            'email' => 'tanaka@example.com',
            'status' => 'active',
        ]);
        $admin->roles()->attach($adminRole->id);

        $officeWorker = User::factory()->create([
            'user_code' => '1002',
            'name' => '山田花子',
            'name_kana' => 'やまだはなこ',
            'email' => 'yamada@example.com',
            'status' => 'leave',
        ]);
        $officeWorker->roles()->attach($officeWorkerRole->id);

        $response = $this->getJson('/api/user');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'userCode',
                        'name',
                        'nameKana',
                        'email',
                        'status',
                        'roles',
                    ],
                ],
            ])
            ->assertJsonFragment([
                'userCode' => 1001,
                'name' => '田中太郎',
                'nameKana' => 'たなかたろう',
                'email' => 'tanaka@example.com',
                'status' => 'active',
                'roles' => ['システム管理者'],
            ])
            ->assertJsonFragment([
                'userCode' => 1002,
                'name' => '山田花子',
                'nameKana' => 'やまだはなこ',
                'email' => 'yamada@example.com',
                'status' => 'leave',
                'roles' => ['事務'],
            ]);
    }
}
