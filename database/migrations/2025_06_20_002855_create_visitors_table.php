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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mobile')->nullable();
            $table->enum('age_group', ['crianca_adolescente', 'jovem', 'adulto', 'idoso'])->nullable();
            $table->enum('gender', ['feminino', 'masculino'])->nullable();
            $table->boolean('wants_contact')->default(false);
            $table->date('visit_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('full_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
