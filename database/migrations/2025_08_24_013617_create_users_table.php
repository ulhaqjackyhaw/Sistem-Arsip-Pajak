<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void
{
Schema::table('users', function (Blueprint $table) {
$table->enum('role', ['admin','officer','vendor'])->default('vendor')->after('password');
$table->string('npwp')->nullable()->index()->after('role');
});


Schema::table('users', function (Blueprint $table) {
$table->foreignId('vendor_id')->nullable()->after('npwp')->constrained('vendors')->nullOnDelete();
});
}


public function down(): void
{
Schema::table('users', function (Blueprint $table) {
$table->dropConstrainedForeignId('vendor_id');
$table->dropColumn(['role','npwp']);
});
}
};