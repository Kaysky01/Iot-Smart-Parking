<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('topups')) {
            Schema::create('topups', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
                $table->bigInteger('amount')->comment('Top-up amount in IDR');
                $table->bigInteger('balance_before')->comment('Balance before top-up');
                $table->bigInteger('balance_after')->comment('Balance after top-up');
                $table->enum('method', ['cash', 'transfer', 'qris', 'other'])->default('cash');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index('user_id');
                $table->index('created_at');
                $table->index('method');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('topups');
    }
};
