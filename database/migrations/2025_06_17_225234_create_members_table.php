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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile');
            $table->enum('gender', ['Masculino', 'Feminino', 'Outro']);
            $table->enum('marital_status', ['Solteiro', 'Casado', 'Divorciado', 'Viúvo'])->nullable();
            $table->date('birth_date');
            $table->date('baptism_date')->nullable();
            $table->date('admission_date')->nullable();
            $table->date('wedding_date')->nullable();
            $table->string('zip_code');
            $table->string('street')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('number')->nullable();
            $table->string('complement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
