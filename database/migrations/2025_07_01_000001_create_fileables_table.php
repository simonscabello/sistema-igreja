<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fileables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('files')->onDelete('cascade');
            $table->morphs('fileable');
            $table->string('collection')->default('default');
            $table->timestamps();
            $table->unique(['file_id', 'fileable_id', 'fileable_type', 'collection'], 'fileables_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fileables');
    }
}; 