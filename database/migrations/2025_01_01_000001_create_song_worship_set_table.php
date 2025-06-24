<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('song_worship_set', function (Blueprint $table) {
            $table->id();
            $table->foreignId('song_id')->constrained()->onDelete('cascade');
            $table->foreignId('worship_set_id')->constrained()->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->string('key_used')->nullable();
            $table->timestamps();

            $table->unique(['song_id', 'worship_set_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('song_worship_set');
    }
}; 