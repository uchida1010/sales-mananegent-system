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
            'user_code' => 1001,
            'name' => '田中太郎',
            'name_kana' => 'たなかたろう',
            'email' => 'tanaka@example.com',
            'status' => 'active',
        ]);
        $admin->roles()->attach($adminRole->id);

        $officeWorker = User::factory()->create([
            'user_code' => 1002,
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

    /**
     * keywordでnameの部分一致検索ができること
     */
    public function test_index_filter_by_keyword_search_name(): void
    {
        User::factory()->create([
            'user_code' => 1001,
            'name' => '田中太郎',
        ]);

        User::factory()->create([
            'user_code' => 1002,
            'name' => '山田花子',
        ]);

        $response = $this->getJson('/api/user?keyword=田中');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'name' => '田中太郎',
            ])
            ->assertJsonMissing([
                'name' => '山田花子',
            ]);
    }

    /**
     * keywordでname_kanaの部分一致検索ができること
     */
    public function test_index_filter_by_keyword_search_name_kana(): void
    {
        User::factory()->create([
            'user_code' => 1001,
            'name' => '田中太郎',
            'name_kana' => 'たなかたろう',
        ]);

        User::factory()->create([
            'user_code' => 1002,
            'name' => '山田花子',
            'name_kana' => 'やまだはなこ',
        ]);

        $response = $this->getJson('/api/user?keyword=たなか');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'nameKana' => 'たなかたろう',
            ])
            ->assertJsonMissing([
                'nameKana' => 'やまだはなこ',
            ]);
    }

    /**
     * userCodeで完全一致検索ができること
     */
    public function test_index_filter_by_user_code(): void
    {
        User::factory()->create([
            'user_code' => 1001,
            'name' => '田中太郎',
            'email' => 'tanaka@example.com',
        ]);

        User::factory()->create([
            'user_code' => 1002,
            'name' => '山田花子',
            'email' => 'yamada@example.com',
        ]);

        $response = $this->getJson('/api/user?userCode=1001');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'userCode' => 1001,
            ])
            ->assertJsonMissing([
                'userCode' => 1002,
            ]);
    }

    /**
     * emailで完全一致検索ができること
     */
    public function test_index_filter_by_email(): void
    {
        User::factory()->create([
            'user_code' => 1001,
            'name' => '田中太郎',
            'email' => 'tanaka@example.com',
        ]);

        User::factory()->create([
            'user_code' => 1002,
            'name' => '山田花子',
            'email' => 'yamada@example.com',
        ]);

        $response = $this->getJson('/api/user?email=tanaka@example.com');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'email' => 'tanaka@example.com',
            ])
            ->assertJsonMissing([
                'email' => 'yamada@example.com',
            ]);
    }

    /**
     * activeOnlyで在職者のみ絞り込みできること
     */
    public function test_index_filter_active_users_only(): void
    {
        User::factory()->create([
            'user_code' => 1001,
            'name' => '田中太郎',
            'status' => 'active',
        ]);

        User::factory()->create([
            'user_code' => 1002,
            'name' => '山田花子',
            'status' => 'leave',
        ]);

        $response = $this->getJson('/api/user?activeOnly=true');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'status' => 'active',
            ])
            ->assertJsonMissing([
                'status' => 'leave',
            ]);
    }

    /**
     * roleIdでロール絞り込みができること
     */
    public function test_index_filter_by_role_id(): void
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
            'user_code' => 1001,
            'name' => '田中太郎',
        ]);
        $admin->roles()->attach($adminRole->id);

        $officeWorker = User::factory()->create([
            'user_code' => 1002,
            'name' => '山田花子',
        ]);
        $officeWorker->roles()->attach($officeWorkerRole->id);

        $response = $this->getJson('/api/user?roleId=' . $adminRole->id);

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'name' => '田中太郎',
            ])
            ->assertJsonMissing([
                'name' => '山田花子',
            ]);
    }
}
