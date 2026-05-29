<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test branch
        $this->branch = Branch::create([
            'code' => 'BPR-TEST-01',
            'name' => 'Test Branch',
            'address' => 'Test Address',
        ]);
    }

    public function test_cs_can_login_with_form_post()
    {
        // Create CS user
        $cs = User::create([
            'name' => 'Test CS',
            'email' => 'cs@test.com',
            'password' => bcrypt('password'),
            'role' => 'cs',
            'branch_id' => $this->branch->id,
            'counter_number' => 2,
        ]);

        // Attempt login via POST
        $response = $this->post('/login', [
            'email' => 'cs@test.com',
            'password' => 'password',
        ]);

        // Should redirect to dashboard
        $response->assertRedirect('/dashboard');
        
        // Verify user is authenticated
        $this->assertAuthenticatedAs($cs);
        
        // Access operator dashboard
        $response = $this->get(route('operator.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Panel Customer Service');
    }

    public function test_admin_can_login_with_form_post()
    {
        // Create Admin user
        $admin = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'branch_id' => $this->branch->id,
            'counter_number' => 3,
        ]);

        // Attempt login via POST
        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        // Should redirect to dashboard
        $response->assertRedirect('/dashboard');
        
        // Verify user is authenticated
        $this->assertAuthenticatedAs($admin);
        
        // Access operator dashboard
        $response = $this->get(route('operator.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Panel Admin');
    }

    public function test_teller_can_login_with_form_post()
    {
        // Create Teller user
        $teller = User::create([
            'name' => 'Test Teller',
            'email' => 'teller@test.com',
            'password' => bcrypt('password'),
            'role' => 'teller',
            'branch_id' => $this->branch->id,
            'counter_number' => 1,
        ]);

        // Attempt login via POST
        $response = $this->post('/login', [
            'email' => 'teller@test.com',
            'password' => 'password',
        ]);

        // Should redirect to dashboard
        $response->assertRedirect('/dashboard');
        
        // Verify user is authenticated
        $this->assertAuthenticatedAs($teller);
        
        // Access operator dashboard
        $response = $this->get(route('operator.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Panel Teller');
    }
}
