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
        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->foreignId('financial_subcategory_id')->nullable()->constrained()->onDelete('restrict');
            $table->dropForeign(['financial_category_id']);
            $table->dropColumn('financial_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->foreignId('financial_category_id')->constrained()->onDelete('restrict');
            $table->dropForeign(['financial_subcategory_id']);
            $table->dropColumn('financial_subcategory_id');
        });
    }
};
