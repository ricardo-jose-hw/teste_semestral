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
        Schema::create('exame', function (Blueprint $table) {
            $table->integer('id_exame')->autoIncrement();

            $table->integer('id_atendimento');
            $table->string('nm_exame', 100);
            $table->string('nm_arquivo', 255);
            $table->string('nm_arquivo_original', 255);
            $table->string('ds_observacao', 255)->nullable();

            $table->timestamps();

            $table->foreign('id_atendimento')->references('id_atendimento')->on('atendimento')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exame');
    }
};
