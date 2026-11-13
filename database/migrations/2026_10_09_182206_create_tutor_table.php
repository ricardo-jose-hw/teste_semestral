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
        Schema::create('tutor', function (Blueprint $table) {
            $table->integer('id_tutor')->autoIncrement();

            $table->string('nm_tutor', 100);
            $table->string('nm_endereco_tutor', 150)->nullable();
            $table->string('nm_email_tutor', 120)->nullable();
            $table->char('cd_telefone_tutor', 11)->nullable();
            $table->char('cd_cpf_tutor', 11)->nullable();
            $table->boolean('ic_ativo_tutor')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor');
    }
};
