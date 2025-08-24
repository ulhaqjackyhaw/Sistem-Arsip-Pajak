<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('vendors', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('vendors', 'npwp')) {
                $table->string('npwp', 32)->unique(); // jaga-jaga bila belum unique
            }
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
            // jangan drop npwp unik kalau sudah dipakai… tapi kalau perlu:
            // $table->dropUnique(['npwp']);
        });
    }
};
