<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Queue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QueueSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $branch;
    protected $superadmin;
    protected $teller;
    protected $cs;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed branch
        $this->branch = Branch::create([
            'code' => 'BPR-TEST-01',
            'name' => 'Test Branch',
            'address' => 'Test Address',
        ]);

        // Create users
        $this->superadmin = User::create([
            'name' => 'Test Superadmin',
            'username' => 'superadmin_test',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
        ]);

        $this->teller = User::create([
            'name' => 'Test Teller',
            'username' => 'teller_test',
            'password' => bcrypt('password'),
            'role' => 'teller',
            'branch_id' => $this->branch->id,
            'counter_number' => 1,
        ]);

        $this->cs = User::create([
            'name' => 'Test CS',
            'username' => 'cs_test',
            'password' => bcrypt('password'),
            'role' => 'cs',
            'branch_id' => $this->branch->id,
            'counter_number' => 2,
        ]);

        $this->admin = User::create([
            'name' => 'Test Admin',
            'username' => 'admin_test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'branch_id' => $this->branch->id,
            'counter_number' => 3,
        ]);
    }

    // ========================
    // AUTH TESTS
    // ========================

    public function test_home_redirects_to_login()
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_login_page_loads_correctly()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_superadmin_can_login_and_access_dashboard()
    {
        $response = $this->actingAs($this->superadmin)->get('/dashboard');
        $response->assertRedirect(route('superadmin.dashboard'));

        $response = $this->actingAs($this->superadmin)->get(route('superadmin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Dashboard Superadmin');
    }

    public function test_teller_can_login_and_access_operator_dashboard()
    {
        $response = $this->actingAs($this->teller)->get('/dashboard');
        $response->assertRedirect(route('operator.dashboard'));

        $response = $this->actingAs($this->teller)->get(route('operator.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Sedang Dilayani');
    }

    public function test_cs_can_login_and_access_operator_dashboard()
    {
        $response = $this->actingAs($this->cs)->get('/dashboard');
        $response->assertRedirect(route('operator.dashboard'));

        $response = $this->actingAs($this->cs)->get(route('operator.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Sedang Dilayani');
    }

    public function test_admin_can_login_and_access_operator_dashboard()
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertRedirect(route('operator.dashboard'));

        $response = $this->actingAs($this->admin)->get(route('operator.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Sedang Dilayani');
    }

    // ========================
    // KIOSK TESTS
    // ========================

    public function test_kiosk_page_loads_with_three_service_types()
    {
        $response = $this->get('/kiosk?branch=' . $this->branch->id);
        $response->assertStatus(200);
        $response->assertSee('Silakan Pilih Layanan');
        // Teller services
        $response->assertSee('Setoran Tunai');
        $response->assertSee('Penarikan Tunai');
        // CS services
        $response->assertSee('Buka Rekening');
        $response->assertSee('Pengaduan');
        // Admin services
        $response->assertSee('Pemindahbukuan');
        $response->assertSee('Cetak Mutasi Rekening');
    }

    public function test_teller_queue_can_be_created()
    {
        $response = $this->post('/kiosk/ticket', [
            'branch_id' => $this->branch->id,
            'customer_note' => 'setoran_tunai',
        ]);

        $response->assertStatus(200);
        $response->assertSee('T-001');
        
        $this->assertDatabaseHas('queues', [
            'branch_id' => $this->branch->id,
            'queue_number' => 'T-001',
            'service_type' => 'teller',
        ]);
    }

    public function test_cs_queue_can_be_created()
    {
        $response = $this->post('/kiosk/ticket', [
            'branch_id' => $this->branch->id,
            'customer_note' => 'buka_rekening',
        ]);

        $response->assertStatus(200);
        $response->assertSee('CS-001');
        
        $this->assertDatabaseHas('queues', [
            'queue_number' => 'CS-001',
            'service_type' => 'cs',
        ]);
    }

    public function test_admin_queue_can_be_created()
    {
        $response = $this->post('/kiosk/ticket', [
            'branch_id' => $this->branch->id,
            'customer_note' => 'pemindahbukuan',
        ]);

        $response->assertStatus(200);
        $response->assertSee('A-001');
        
        $this->assertDatabaseHas('queues', [
            'queue_number' => 'A-001',
            'service_type' => 'admin',
        ]);
    }

    public function test_queue_numbers_increment_correctly()
    {
        // Create first teller queue
        $this->post('/kiosk/ticket', [
            'branch_id' => $this->branch->id,
            'customer_note' => 'setoran_tunai',
        ]);

        // Create second teller queue
        $response = $this->post('/kiosk/ticket', [
            'branch_id' => $this->branch->id,
            'customer_note' => 'penarikan_tunai',
        ]);

        $response->assertSee('T-002');
    }

    // ========================
    // OPERATOR TESTS
    // ========================

    public function test_teller_can_call_next_queue()
    {
        Queue::create([
            'branch_id' => $this->branch->id,
            'queue_number' => 'T-001',
            'service_type' => 'teller',
            'customer_note' => 'setoran_tunai',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->teller)->post(route('operator.call-next'));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('queues', [
            'queue_number' => 'T-001',
            'status' => 'in_process',
            'served_by' => $this->teller->id,
            'counter_number' => 1,
        ]);
    }

    public function test_admin_can_call_admin_queue()
    {
        Queue::create([
            'branch_id' => $this->branch->id,
            'queue_number' => 'A-001',
            'service_type' => 'admin',
            'customer_note' => 'pemindahbukuan',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(route('operator.call-next'));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('queues', [
            'queue_number' => 'A-001',
            'status' => 'in_process',
            'served_by' => $this->admin->id,
            'counter_number' => 3,
        ]);
    }

    public function test_operator_can_finish_queue()
    {
        Queue::create([
            'branch_id' => $this->branch->id,
            'queue_number' => 'T-001',
            'service_type' => 'teller',
            'customer_note' => 'setoran_tunai',
            'status' => 'in_process',
            'served_by' => $this->teller->id,
            'counter_number' => 1,
            'called_at' => now(),
        ]);

        $response = $this->actingAs($this->teller)->post(route('operator.finish'));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('queues', [
            'queue_number' => 'T-001',
            'status' => 'finished',
        ]);
    }

    public function test_operator_can_skip_queue()
    {
        Queue::create([
            'branch_id' => $this->branch->id,
            'queue_number' => 'T-001',
            'service_type' => 'teller',
            'customer_note' => 'setoran_tunai',
            'status' => 'in_process',
            'served_by' => $this->teller->id,
            'counter_number' => 1,
            'called_at' => now(),
        ]);

        $response = $this->actingAs($this->teller)->post(route('operator.skip'));
        $response->assertRedirect();
        $response->assertSessionHas('warning');

        $this->assertDatabaseHas('queues', [
            'queue_number' => 'T-001',
            'status' => 'skipped',
        ]);
    }

    // ========================
    // REAL-TIME STATUS API TESTS
    // ========================

    public function test_operator_status_api_returns_correct_data()
    {
        $response = $this->actingAs($this->teller)->get(route('operator.status'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'pending_count',
            'pending_queues',
            'recent_queues',
            'served_by_me',
            'total_today',
            'current_queue',
            'timestamp',
        ]);
    }

    public function test_kiosk_status_api_returns_all_service_types()
    {
        $response = $this->get('/kiosk/status?branch=' . $this->branch->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'pending_teller',
            'pending_cs',
            'pending_admin',
        ]);
    }

    // ========================
    // DISPLAY TESTS
    // ========================

    public function test_display_page_loads_correctly()
    {
        $response = $this->get('/display/' . $this->branch->id);
        $response->assertStatus(200);
        $response->assertSee('Test Branch');
    }

    public function test_display_data_api_returns_json()
    {
        $response = $this->get('/display/' . $this->branch->id . '/data');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'display_queues',
            'pending_counts' => ['teller', 'cs', 'admin'],
            'media',
            'timestamp',
        ]);
    }

    // ========================
    // SUPERADMIN TESTS
    // ========================

    public function test_teller_cannot_access_superadmin_dashboard()
    {
        $response = $this->actingAs($this->teller)->get(route('superadmin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_superadmin_can_view_branches()
    {
        $response = $this->actingAs($this->superadmin)->get(route('superadmin.branches'));
        $response->assertStatus(200);
        $response->assertSee('Test Branch');
    }

    public function test_superadmin_can_view_users()
    {
        $response = $this->actingAs($this->superadmin)->get(route('superadmin.users'));
        $response->assertStatus(200);
    }

    public function test_superadmin_can_view_reports()
    {
        $response = $this->actingAs($this->superadmin)->get(route('superadmin.reports'));
        $response->assertStatus(200);
        $response->assertSee('Laporan');
    }

    // ========================
    // SERVICE TYPE ISOLATION TESTS
    // ========================

    public function test_teller_only_sees_teller_queues()
    {
        // Create teller queue
        Queue::create([
            'branch_id' => $this->branch->id,
            'queue_number' => 'T-001',
            'service_type' => 'teller',
            'customer_note' => 'setoran_tunai',
            'status' => 'pending',
        ]);
        
        // Create admin queue
        Queue::create([
            'branch_id' => $this->branch->id,
            'queue_number' => 'A-001',
            'service_type' => 'admin',
            'customer_note' => 'pemindahbukuan',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->teller)->get(route('operator.status'));
        $response->assertStatus(200);
        $data = $response->json();
        
        $this->assertEquals(1, $data['pending_count']);
        $this->assertCount(1, $data['pending_queues']);
        $pendingQueues = collect($data['pending_queues']);
        $this->assertTrue($pendingQueues->contains('queue_number', 'T-001'));
    }

    public function test_admin_only_sees_admin_queues()
    {
        // Create teller queue
        Queue::create([
            'branch_id' => $this->branch->id,
            'queue_number' => 'T-001',
            'service_type' => 'teller',
            'customer_note' => 'setoran_tunai',
            'status' => 'pending',
        ]);
        
        // Create admin queue
        Queue::create([
            'branch_id' => $this->branch->id,
            'queue_number' => 'A-001',
            'service_type' => 'admin',
            'customer_note' => 'pemindahbukuan',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get(route('operator.status'));
        $response->assertStatus(200);
        $data = $response->json();
        
        $this->assertEquals(1, $data['pending_count']);
        $this->assertCount(1, $data['pending_queues']);
        $pendingQueues = collect($data['pending_queues']);
        $this->assertTrue($pendingQueues->contains('queue_number', 'A-001'));
    }
}
