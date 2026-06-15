<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modify users table - add rfid_uid and balance if not exists
        if (!Schema::hasColumn('users', 'rfid_uid')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('rfid_uid')->unique()->nullable()->after('name');
                $table->bigInteger('balance')->default(0)->after('rfid_uid');
            });
        }

        // Create parkings table
        if (!Schema::hasTable('parkings')) {
            Schema::create('parkings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->timestamp('entry_time');
                $table->timestamp('exit_time')->nullable();
                $table->integer('duration')->nullable()->comment('Duration in hours');
                $table->bigInteger('cost')->nullable()->comment('Cost in IDR');
                $table->enum('status', ['IN', 'OUT'])->default('IN');
                $table->timestamps();

                $table->index(['user_id', 'status']);
                $table->index('entry_time');
            });
        }

        // Create transactions table
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('parking_id')->constrained()->onDelete('cascade');
                $table->bigInteger('amount');
                $table->bigInteger('remaining_balance');
                $table->timestamps();

                $table->index('user_id');
                $table->index('created_at');
            });
        }

        // Create activity_logs table (bonus)
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->string('type'); // entry, exit, registration, payment
                $table->string('description');
                $table->json('metadata')->nullable();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->timestamps();

                $table->index('type');
                $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('parkings');

        if (Schema::hasColumn('users', 'rfid_uid')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['rfid_uid', 'balance']);
            });
        }
    }
};
