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
        Schema::table('categories', function (Blueprint $table) {
            // Add parent_id column to support hierarchical categories
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');

            // Add level to track hierarchy depth (0 = main category, 1 = subcategory)
            $table->tinyInteger('level')->default(0)->after('parent_id');

            // Update existing columns
            $table->string('icon')->nullable()->after('name'); // For category icons
            $table->text('description')->nullable()->after('icon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'level', 'icon', 'description']);
        });
    }
};
