<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_worship_function', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worship_function_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['worship_function_id', 'member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_worship_function');
    }
};
