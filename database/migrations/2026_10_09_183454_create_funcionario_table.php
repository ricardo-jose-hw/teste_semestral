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
        Schema::create('funcionario', function (Blueprint $table) {
            $table->integer('id_funcionario')->autoIncrement();

            $table->string('nm_funcionario', 100);
            $table->string('nm_email_funcionario', 120)->unique();
            $table->char('cd_cpf_funcionario', 11)->unique();
            $table->char('cd_telefone_funcionario', 11);
            $table->string('nm_especialidade_funcionario', 80)->nullable();
            $table->string('cd_crmv_funcionario', 80)->nullable();
            $table->boolean('ic_ativo_funcionario')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionario');
    }
};
