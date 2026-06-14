<?php

namespace Tests\Feature;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 役割一覧APIが、役割一覧のリソース形状を期待通りに返すこと
     */
    public function test_index_returns_roles_with_expected_resource_shape(): void
    {
        Role::create([
            'name' => 'システム管理者',
            'code' => 'admin',
        ]);

        Role::create([
            'name' => '事務',
            'code' => 'office_worker',
        ]);

        $response = $this->getJson('/api/roles');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'name',
                    ],
                ],
            ])
            ->assertJsonFragment([
                'name' => 'システム管理者',
            ])
            ->assertJsonFragment([
                'name' => '事務',
            ]);
    }
}
