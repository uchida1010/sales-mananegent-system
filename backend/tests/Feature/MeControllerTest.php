<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_protected_apis(): void
    {
        foreach (['/api/me', '/api/user', '/api/roles'] as $path) {
            $this->getJson($path)->assertUnauthorized();
        }
    }
}
