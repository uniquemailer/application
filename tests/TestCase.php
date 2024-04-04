<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use LazilyRefreshDatabase;

    protected function setUp(): void
{
    parent::setUp();
 
    Role::create(['name' => 'admin']);
    
    $this->actingAs(
        User::factory()->admin()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])
    );
}
}
