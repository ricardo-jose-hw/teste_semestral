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
        Schema::create('pet', function (Blueprint $table) {
            $table->integer('id_pet')->autoIncrement();

            $table->string('nm_pet', 50);
            $table->string('nm_especie_pet', 50);
            $table->string('nm_raca_pet', 50)->nullable();
            $table->decimal('qt_peso_pet', 5, 2)->nullable();
            $table->integer('id_tutor');
            $table->boolean('ic_ativo_pet')->default(true);

            $table->timestamps();

            $table->foreign('id_tutor')->references('id_tutor')->on('tutor')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet');
    }
};
