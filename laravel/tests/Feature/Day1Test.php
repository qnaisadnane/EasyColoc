<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Day1Test extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the first user registered becomes a global admin.
     */
    public function test_first_user_is_promoted_to_admin(): void
    {
        $response = $this->post('/register', [
            'name' => 'First User',
            'email' => 'first@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/dashboard');

        $user = \App\Models\User::where('email', 'first@example.com')->first();
        $this->assertEquals('admin', $user->role);
    }

    /**
     * Test that subsequent users are members.
     */
    public function test_subsequent_users_are_members(): void
    {
        
        \App\Models\User::factory()->create(['role' => 'admin']);

        $response = $this->post('/register', [
            'name' => 'Second User',
            'email' => 'second@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/dashboard');

        $user = \App\Models\User::where('email', 'second@example.com')->first();
        $this->assertEquals('member', $user->role);
    }

    
    public function test_banned_user_is_redirected_to_login_with_error(): void
    {
        $user = \App\Models\User::factory()->create([
            'is_banned' => true,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/login');
        $this->assertFalse(\Illuminate\Support\Facades\Auth::check());
        $response->assertSessionHas('error', 'Your account has been banned.');
    }
}
