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
        Schema::create('atendimento', function (Blueprint $table) {
            $table->integer('id_atendimento')->autoIncrement();

            $table->integer('PET_id_pet');
            $table->integer('FUNCIONARIO_id_funcionario');
            $table->foreignId('clinica_id')->nullable()->constrained('clinicas')->nullOnDelete();

            $table->date('dt_atendimento');
            $table->time('hr_atendimento');
            $table->string('ds_motivo', 255)->nullable();
            $table->string('ic_status_atendimento', 20)->default('Pendente');

            $table->string('nm_tipo_atendimento', 50)->nullable();
            $table->decimal('vl_atendimento', 7, 2)->nullable();

            $table->timestamps();

            $table->foreign('PET_id_pet')->references('id_pet')->on('pet')->cascadeOnDelete();
            $table->foreign('FUNCIONARIO_id_funcionario')->references('id_funcionario')->on('funcionario')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atendimento');
    }
};
