<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('original_name');
            $table->string('extension', 20);
            $table->unsignedBigInteger('size');
            $table->string('mime_type', 100);
            $table->string('path');
            $table->string('disk');
            $table->string('url');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
}; 