<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $role = Role::create(['name' => 'Member', 'slug' => 'member']);

        $response = $this->postJson('/api/v1/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['user', 'token']);
        
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_user_can_login()
    {
        $role = Role::create(['name' => 'Member', 'slug' => 'member']);
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'login@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_user_can_get_profile()
    {
        $role = Role::create(['name' => 'Member', 'slug' => 'member']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/me');

        $response->assertStatus(200)
            ->assertJsonPath('data.email', $user->email);
    }
}
