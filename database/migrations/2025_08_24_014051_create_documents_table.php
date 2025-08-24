<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void
{
Schema::create('documents', function (Blueprint $table) {
$table->id();
$table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
$table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
$table->string('period', 7); // format YYYY-MM
$table->string('original_name');
$table->string('stored_name');
$table->string('mime', 128);
$table->unsignedBigInteger('size');
$table->string('hash', 64)->nullable();
$table->string('path'); // path relatif pada disk private
$table->timestamps();


$table->index(['vendor_id','period']);
});
}


public function down(): void
{
Schema::dropIfExists('documents');
}
};