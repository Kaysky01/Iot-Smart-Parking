<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'npm')) {
                $table->string('npm')->unique()->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'plate_number')) {
                $table->string('plate_number')->nullable()->after('rfid_uid');
            }
            if (!Schema::hasColumn('users', 'vehicle_type')) {
                $table->string('vehicle_type')->nullable()->after('plate_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['npm', 'plate_number', 'vehicle_type']);
        });
    }
};
