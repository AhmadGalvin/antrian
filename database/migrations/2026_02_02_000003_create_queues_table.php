<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->string('queue_number', 10)->comment('Format: T-001, CS-001');
            $table->enum('service_type', ['teller', 'cs', 'admin'])->comment('Tipe layanan');
            $table->string('customer_note', 50)->comment('Jenis transaksi: setoran_tunai, penarikan_tunai, buka_rekening, dll');
            $table->enum('status', ['pending', 'in_process', 'finished', 'skipped'])->default('pending');
            $table->unsignedTinyInteger('counter_number')->nullable()->comment('Nomor loket yang melayani');
            $table->foreignId('served_by')->nullable()->constrained('users')->nullOnDelete()->comment('User yang melayani');
            $table->timestamp('called_at')->nullable()->comment('Waktu dipanggil');
            $table->timestamp('finished_at')->nullable()->comment('Waktu selesai');
            $table->timestamps();

            // Indexes for performance
            $table->index(['branch_id', 'status', 'created_at']);
            $table->index(['branch_id', 'service_type', 'status']);
            $table->index(['branch_id', 'queue_number', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
