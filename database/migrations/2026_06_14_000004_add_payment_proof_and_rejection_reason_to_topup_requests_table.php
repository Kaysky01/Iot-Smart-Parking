<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('topup_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('topup_requests', 'payment_proof_path')) {
                $table->string('payment_proof_path')->nullable()->after('amount');
            }
            if (!Schema::hasColumn('topup_requests', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('approved_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('topup_requests', function (Blueprint $table) {
            $table->dropColumn(['payment_proof_path', 'rejection_reason']);
        });
    }
};
