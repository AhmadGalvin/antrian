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
            'username' => 'cs_test',
            'password' => bcrypt('password'),
            'role' => 'cs',
            'branch_id' => $this->branch->id,
            'counter_number' => 2,
        ]);

        // Attempt login via POST
        $response = $this->post('/login', [
            'username' => 'cs_test',
            'password' => 'password',
        ]);

        // Should redirect to dashboard
        $response->assertRedirect('/dashboard');
        
        // Verify user is authenticated
        $this->assertAuthenticatedAs($cs);
        
        // Access operator dashboard
        $response = $this->get(route('operator.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Customer Service');
    }

    public function test_admin_can_login_with_form_post()
    {
        // Create Admin user
        $admin = User::create([
            'name' => 'Test Admin',
            'username' => 'admin_test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'branch_id' => $this->branch->id,
            'counter_number' => 3,
        ]);

        // Attempt login via POST
        $response = $this->post('/login', [
            'username' => 'admin_test',
            'password' => 'password',
        ]);

        // Should redirect to dashboard
        $response->assertRedirect('/dashboard');
        
        // Verify user is authenticated
        $this->assertAuthenticatedAs($admin);
        
        // Access operator dashboard
        $response = $this->get(route('operator.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Admin');
    }

    public function test_teller_can_login_with_form_post()
    {
        // Create Teller user
        $teller = User::create([
            'name' => 'Test Teller',
            'username' => 'teller_test',
            'password' => bcrypt('password'),
            'role' => 'teller',
            'branch_id' => $this->branch->id,
            'counter_number' => 1,
        ]);

        // Attempt login via POST
        $response = $this->post('/login', [
            'username' => 'teller_test',
            'password' => 'password',
        ]);

        // Should redirect to dashboard
        $response->assertRedirect('/dashboard');
        
        // Verify user is authenticated
        $this->assertAuthenticatedAs($teller);
        
        // Access operator dashboard
        $response = $this->get(route('operator.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Teller');
    }
}
