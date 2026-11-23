<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('position')->nullable()->after('shelf');
        });
        Schema::create('roll_location_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('roll_id')->constrained('rolls');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('from_location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('to_location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->string('reason')->default('Reubicación');
            $table->timestamps();
            $table->index(['roll_id', 'created_at']);
        });
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->index(['entity_type', 'entity_id']);
            $table->index(['created_at', 'action']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('roll_location_movements');
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};
