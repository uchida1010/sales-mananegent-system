<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Notifications\InitialPasswordSetupNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
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
            'user_code' => 'E001',
            'name' => '田中太郎',
            'name_kana' => 'たなかたろう',
            'email' => 'tanaka@example.com',
            'status' => 'active',
        ]);
        $admin->roles()->attach($adminRole->id);

        $officeWorker = User::factory()->create([
            'user_code' => 'E002',
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
                'userCode' => 'E001',
                'name' => '田中太郎',
                'nameKana' => 'たなかたろう',
                'email' => 'tanaka@example.com',
                'status' => 'active',
                'roles' => [
                    [
                        'id' => $adminRole->id,
                        'name' => 'システム管理者',
                    ],
                ],
            ])
            ->assertJsonFragment([
                'userCode' => 'E002',
                'name' => '山田花子',
                'nameKana' => 'やまだはなこ',
                'email' => 'yamada@example.com',
                'status' => 'leave',
                'roles' => [
                    [
                        'id' => $adminRole->id,
                        'name' => 'システム管理者',
                    ],
                ],
            ]);
    }

    /**
     * keywordでnameの部分一致検索ができること
     */
    public function test_index_filter_by_keyword_search_name(): void
    {
        User::factory()->create([
            'user_code' => 'E001',
            'name' => '田中太郎',
        ]);

        User::factory()->create([
            'user_code' => 'E002',
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
            'user_code' => 'E001',
            'name' => '田中太郎',
            'name_kana' => 'たなかたろう',
        ]);

        User::factory()->create([
            'user_code' => 'E002',
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
            'user_code' => 'E001',
            'name' => '田中太郎',
            'email' => 'tanaka@example.com',
        ]);

        User::factory()->create([
            'user_code' => 'E002',
            'name' => '山田花子',
            'email' => 'yamada@example.com',
        ]);

        $response = $this->getJson('/api/user?userCode=E001');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'userCode' => 'E001',
            ])
            ->assertJsonMissing([
                'userCode' => 'E002',
            ]);
    }

    /**
     * emailで完全一致検索ができること
     */
    public function test_index_filter_by_email(): void
    {
        User::factory()->create([
            'user_code' => 'E001',
            'name' => '田中太郎',
            'email' => 'tanaka@example.com',
        ]);

        User::factory()->create([
            'user_code' => 'E002',
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
            'user_code' => 'E001',
            'name' => '田中太郎',
            'status' => 'active',
        ]);

        User::factory()->create([
            'user_code' => 'E002',
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
            'user_code' => 'E001',
            'name' => '田中太郎',
        ]);
        $admin->roles()->attach($adminRole->id);

        $officeWorker = User::factory()->create([
            'user_code' => 'E002',
            'name' => '山田花子',
        ]);
        $officeWorker->roles()->attach($officeWorkerRole->id);

        $response = $this->getJson('/api/user?roleId='.$adminRole->id);

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
     * ユーザーを正常に更新できること
     */
    public function test_update_user_successfully(): void
    {
        $user = User::factory()->create([
            'user_code' => 'E001',
            'name' => '田中太郎',
            'email' => 'tanaka@example.com',
        ]);

        $role = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $user->roles()->attach($role->id);

        $response = $this->putJson('/api/user/E001', [
            'userCode' => 'E001',
            'name' => '山田太郎',
            'email' => 'tanaka@gmail.com',
            'status' => 'active',
            'joined_at' => '2026-04-01',
            'roleId' => (string) $role->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'user_code' => 'E001',
            'name' => '山田太郎',
        ]);
    }

    /**
     * 名前が空文字の場合、更新できないこと
     */
    public function test_update_fails_when_name_is_empty(): void
    {
        $user = User::factory()->create([
            'user_code' => 'E001',
            'name' => '田中太郎',
            'email' => 'tanaka@example.com',
        ]);

        $role = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $user->roles()->attach($role->id);

        $response = $this->putJson('/api/user/E001', [
            'userCode' => 'E001',
            'name' => '',
            'email' => 'tanaka@gmail.com',
            'phone' => '09012345678',
            'status' => 'active',
            'joined_at' => '2026-04-01',
            'roleId' => (string) $role->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        $this->assertDatabaseHas('users', [
            'user_code' => 'E001',
            'name' => '田中太郎',
        ]);
    }

    /**
     * 他のユーザーとメールアドレスが重複する場合、更新できないこと
     */
    public function test_update_fails_when_email_is_duplicate(): void
    {
        $user1 = User::factory()->create([
            'user_code' => 'E001',
            'name' => '田中太郎',
            'email' => 'tanaka@gmail.com',
        ]);

        $user2 = User::factory()->create([
            'user_code' => 'E002',
            'name' => '山田花子',
            'email' => 'yamada@gmail.com',
        ]);

        $role = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $user1->roles()->attach($role->id);
        $user2->roles()->attach($role->id);

        $response = $this->putJson('/api/user/E001', [
            'userCode' => 'E001',
            'name' => '田中太郎',
            'email' => 'yamada@gmail.com',
            'status' => 'active',
            'joined_at' => '2026-04-01',
            'roleId' => (string) $role->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);

        $this->assertDatabaseHas('users', [
            'user_code' => 'E001',
            'email' => 'tanaka@gmail.com',
        ]);
    }

    /**
     * ユーザー自身のメールアドレスの場合、更新できること
     */
    public function test_update_succeeds_with_own_email(): void
    {
        $user = User::factory()->create([
            'user_code' => 'E001',
            'name' => '田中太郎',
            'email' => 'tanaka@gmail.com',
        ]);

        $role = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $user->roles()->attach($role->id);

        $response = $this->putJson('/api/user/E001', [
            'userCode' => 'E001',
            'name' => '田中一郎',
            'email' => 'tanaka@gmail.com',
            'phone' => '09012345678',
            'status' => 'active',
            'joined_at' => '2026-04-01',
            'roleId' => (string) $role->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'user_code' => 'E001',
            'name' => '田中一郎',
            'email' => 'tanaka@gmail.com',
        ]);
    }

    /**
     * 存在しないユーザーの更新はできないこと
     */
    public function test_update_fails_when_user_not_found(): void
    {
        $user = User::factory()->create([
            'user_code' => 'E001',
            'name' => '田中太郎',
            'email' => 'tanaka@gmail.com',
        ]);

        $role = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $user->roles()->attach($role->id);

        $response = $this->putJson('/api/user/E999', [
            'userCode' => 'E999',
            'name' => '山田太郎',
            'email' => 'yamada@gmail.com',
            'status' => 'active',
            'joined_at' => '2026-04-01',
            'roleId' => '1',
        ]);

        $response->assertStatus(404);

        $this->assertDatabaseMissing('users', [
            'user_code' => 'E999',
        ]);
    }

    /**
     * 日付形式ではない入社日の更新はできないこと
     */
    public function test_update_fails_when_joined_at_is_invalid(): void
    {
        $user = User::factory()->create([
            'user_code' => 'E001',
            'name' => '田中太郎',
            'email' => 'tanaka@gmail.com',
            'joined_at' => '2026-04-01',
        ]);

        $role = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $user->roles()->attach($role->id);

        $response = $this->putJson('/api/user/E001', [
            'userCode' => 'E001',
            'name' => '田中太郎',
            'email' => 'tanaka@gmail.com',
            'phone' => '09012345678',
            'status' => 'active',
            'joined_at' => 'abc',
            'roleId' => (string) $role->id,
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['joined_at']);

        $this->assertDatabaseHas('users', [
            'user_code' => 'E001',
            'joined_at' => '2026-04-01 00:00:00',
        ]);
    }

    /**
     * ユーザーの権限を正常に更新できること
     */
    public function test_update_user_role_successfully(): void
    {

        $user = User::factory()->create([
            'user_code' => 'E001',
            'name' => '田中太郎',
            'email' => 'tanaka@gmail.com',
        ]);

        $adminRole = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $officeRole = Role::create([
            'name' => '事務',
            'code' => 'office_worker',
        ]);

        $user->roles()->attach($adminRole->id);

        $response = $this->putJson('/api/user/E001', [
            'userCode' => 'E001',
            'name' => '田中太郎',
            'email' => 'tanaka@gmail.com',
            'phone' => '09012345678',
            'status' => 'active',
            'joined_at' => '2026-04-01',
            'roleId' => (string) $officeRole->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $officeRole->id,
        ]);

        $this->assertDatabaseMissing('role_user', [
            'user_id' => $user->id,
            'role_id' => $adminRole->id,
        ]);
    }

    /**
     * 他のユーザーとユーザーコードが重複する場合、更新できないこと
     */
    public function test_update_fails_when_user_code_is_duplicate(): void
    {
        $user1 = User::factory()->create([
            'user_code' => 'E001',
            'name' => '田中太郎',
            'email' => 'tanaka@gmail.com',
        ]);

        $user2 = User::factory()->create([
            'user_code' => 'E002',
            'name' => '山田花子',
            'email' => 'yamada@gmail.com',
        ]);

        $role = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $user1->roles()->attach($role->id);

        $response = $this->putJson('/api/user/E001', [
            'userCode' => 'E002',
            'name' => '田中太郎',
            'email' => 'tanaka@gmail.com',
            'phone' => '09012345678',
            'status' => 'active',
            'joined_at' => '2026-04-01',
            'roleId' => (string) $role->id,
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['userCode']);

        $this->assertDatabaseHas('users', [
            'user_code' => 'E001',
            'name' => '田中太郎',
        ]);

        $this->assertDatabaseMissing('users', [
            'user_code' => 'E002',
            'name' => '田中太郎',
        ]);
    }

    /**
     * メールアドレスの形式が不正な場合、更新できないこと
     */
    public function test_update_fails_when_email_is_invalid(): void
    {
        $user = User::factory()->create([
            'user_code' => 'E001',
            'name' => '田中太郎',
            'email' => 'tanaka@gmail.com',
        ]);

        $role = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $user->roles()->attach($role->id);

        $response = $this->putJson('/api/user/E001', [
            'userCode' => 'E001',
            'name' => '田中太郎',
            'email' => 'invalid-email',
            'phone' => '09012345678',
            'status' => 'active',
            'joined_at' => '2026-04-01',
            'roleId' => (string) $role->id,
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['email']);

        $this->assertDatabaseHas('users', [
            'user_code' => 'E001',
            'email' => 'tanaka@gmail.com',
        ]);

        $this->assertDatabaseMissing('users', [
            'user_code' => 'E001',
            'email' => 'invalid-email',
        ]);
    }

    /**
     * ステータスが不正な場合、更新できないこと
     */
    public function test_update_fails_when_status_is_invalid(): void
    {
        $user = User::factory()->create([
            'user_code' => 'E001',
            'name' => '田中太郎',
            'email' => 'tanaka@gmail.com',
            'status' => 'active',
        ]);

        $role = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $user->roles()->attach($role->id);

        $response = $this->putJson('/api/user/E001', [
            'userCode' => 'E001',
            'name' => '田中太郎',
            'email' => 'tanaka@gmail.com',
            'phone' => '09012345678',
            'status' => 'invalid_status',
            'joined_at' => '2026-04-01',
            'roleId' => (string) $role->id,
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['status']);

        $this->assertDatabaseHas('users', [
            'user_code' => 'E001',
            'status' => 'active',
        ]);

        $this->assertDatabaseMissing('users', [
            'user_code' => 'E001',
            'status' => 'invalid_status',
        ]);
    }

    /**
     * 必須項目が抜けている場合、更新できないこと
     */
    public function test_update_fails_when_required_field_is_missing(): void
    {
        $user = User::factory()->create([
            'user_code' => 'E001',
            'name' => '田中太郎',
            'email' => 'tanaka@gmail.com',
        ]);

        $role = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $user->roles()->attach($role->id);

        $response = $this->putJson('/api/user/E001', [
            'userCode' => 'E001',
            'email' => 'tanaka@gmail.com',
            'phone' => '09012345678',
            'status' => 'active',
            'joined_at' => '2026-04-01',
            'roleId' => (string) $role->id,
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['name']);

        $this->assertDatabaseHas('users', [
            'user_code' => 'E001',
            'name' => '田中太郎',
        ]);
    }

    /**
     * ユーザーを正常に削除できること
     */
    public function test_destroy_deletes_user(): void
    {
        $user = User::factory()->create([
            'user_code' => 'E001',
        ]);

        $response = $this->deleteJson('/api/user/E001');

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', [
            'user_code' => 'E001',
        ]);
    }

    /**
     * 存在しないユーザーは削除できないこと
     */
    public function test_destroy_returns_404_when_user_not_found(): void
    {
        $response = $this->deleteJson('/api/user/E999');

        $response->assertStatus(404);
    }

    /**
     * ユーザーが正常に登録されること、
     * メール通知が正常に行われること
     */
    public function test_store_creates_user(): void
    {
        Notification::fake();

        $role = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $response = $this->postJson('/api/user', [
            'userCode' => 'E010',
            'name' => '佐藤太郎',
            'email' => 'sato@gmail.com',
            'joined_at' => '2026-04-01',
            'roleId' => (string) $role->id,
        ]);

        $response->assertStatus(201);

        $user = User::where('user_code', 'E010')->first();

        $this->assertNotNull($user);

        $this->assertDatabaseHas('users', [
            'user_code' => 'E010',
            'name' => '佐藤太郎',
            'email' => 'sato@gmail.com',
        ]);

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);

        Notification::assertSentTo(
            $user,
            InitialPasswordSetupNotification::class
        );
    }

    /**
     * 氏名が入力されていない場合、登録ができないこと
     */
    public function test_store_fails_when_name_is_missing(): void
    {
        Notification::fake();

        $role = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $response = $this->postJson('/api/user', [
            'userCode' => 'E010',
            'email' => 'sato@example.com',
            'joined_at' => '2026-04-01',
            'roleId' => (string) $role->id,
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['name']);

        $this->assertDatabaseMissing('users', [
            'user_code' => 'E010',
        ]);

        Notification::assertNothingSent();
    }

    /**
     * ユーザーコードが重複している場合、登録できないこと
     */
    public function test_store_fails_when_user_code_is_duplicated(): void
    {
        Notification::fake();

        User::factory()->create([
            'user_code' => 'E010',
        ]);

        $role = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $response = $this->postJson('/api/user', [
            'userCode' => 'E010',
            'name' => '佐藤太郎',
            'email' => 'sato@example.com',
            'joined_at' => '2026-04-01',
            'roleId' => (string) $role->id,
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['userCode']);

        $this->assertDatabaseCount('users', 1);

        Notification::assertNothingSent();
    }

    /**
     * メールが重複している場合、登録できないこと
     */
    public function test_store_fails_when_email_is_duplicated(): void
    {
        Notification::fake();

        User::factory()->create([
            'user_code' => 'E010',
            'email' => 'sato@example.com',
        ]);

        $role = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $response = $this->postJson('/api/user', [
            'userCode' => 'E011',
            'name' => '佐藤太郎',
            'email' => 'sato@example.com',
            'joined_at' => '2026-04-01',
            'roleId' => (string) $role->id,
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['email']);

        $this->assertDatabaseCount('users', 1);

        Notification::assertNothingSent();
    }

    /**
     * ユーザーコードが「E+3桁の数字」ではない場合、登録できないこと
     */
    public function test_store_fails_when_user_code_format_is_invalid(): void
    {
        Notification::fake();

        $role = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $response = $this->postJson('/api/user', [
            'userCode' => 'E12',
            'name' => '佐藤太郎',
            'email' => 'sato@example.com',
            'joined_at' => '2026-04-01',
            'roleId' => (string) $role->id,
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['userCode']);

        $this->assertDatabaseMissing('users', [
            'user_code' => 'E12',
        ]);

        Notification::assertNothingSent();
    }

    public function test_store_fails_when_role_id_does_not_exist(): void
    {

        Notification::fake();

        $response = $this->postJson('/api/user', [
            'userCode' => 'E010',
            'name' => '佐藤太郎',
            'email' => 'sato@example.com',
            'joined_at' => '2026-04-01',
            'roleId' => '99999',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['roleId']);

        $this->assertDatabaseMissing('users', [
            'user_code' => 'E010',
        ]);

        Notification::assertNothingSent();
    }

    public function test_store_fails_when_joined_at_format_is_invalid(): void
    {
        Notification::fake();

        $role = Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        $response = $this->postJson('/api/user', [
            'userCode' => 'E010',
            'name' => '佐藤太郎',
            'email' => 'sato@example.com',
            'joined_at' => '2026/04/01',
            'roleId' => (string) $role->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['joined_at']);

        $this->assertDatabaseMissing('users', [
            'user_code' => 'E010',
        ]);

        Notification::assertNothingSent();
    }
}
