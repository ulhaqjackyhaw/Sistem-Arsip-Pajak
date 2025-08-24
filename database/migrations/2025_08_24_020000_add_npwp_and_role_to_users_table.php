<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'npwp')) {
                $table->string('npwp', 32)->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 32)->default('vendor')->index()->after('npwp');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropIndex(['role']);
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'npwp')) {
                $table->dropUnique(['npwp']);
                $table->dropColumn('npwp');
            }
        });
    }
};
