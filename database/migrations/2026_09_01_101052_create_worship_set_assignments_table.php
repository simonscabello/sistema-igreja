<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worship_set_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worship_set_id')->constrained()->cascadeOnDelete();
            $table->foreignId('worship_function_id')->constrained()->restrictOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('order')->default(1);
            $table->timestamps();

            $table->unique(['worship_set_id', 'worship_function_id', 'member_id'], 'worship_set_function_member_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worship_set_assignments');
    }
};
