<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('worship_sets', function (Blueprint $table) {
            $table->dropColumn(['singer', 'preacher']);
        });
    }

    public function down(): void
    {
        Schema::table('worship_sets', function (Blueprint $table) {
            $table->string('singer')->after('id');
            $table->string('preacher')->after('singer');
        });
    }
};
