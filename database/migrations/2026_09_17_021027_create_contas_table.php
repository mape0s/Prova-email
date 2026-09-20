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
        Schema::create('contas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('Cliente dono da conta');
            $table->foreignId('gerente_conta_id')->nullable()->constrained('users');
            $table->decimal('saldo', 15, 2)->default(0);
            $table->decimal('limite', 15, 2)->default(0);
            $table->enum('status', ['ativa', 'bloqueada'])->default('ativa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas');
    }
};
