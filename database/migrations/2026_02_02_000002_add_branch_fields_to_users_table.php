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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('id')->constrained('branches')->nullOnDelete();
            $table->enum('role', ['superadmin', 'admin', 'teller', 'cs'])->default('teller')->after('username');
            $table->unsignedTinyInteger('counter_number')->nullable()->after('role')->comment('Nomor Loket');
        });

        // Add indexes for performance
        Schema::table('users', function (Blueprint $table) {
            $table->index(['branch_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['branch_id', 'role']);
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['branch_id', 'role', 'counter_number']);
        });
    }
};
