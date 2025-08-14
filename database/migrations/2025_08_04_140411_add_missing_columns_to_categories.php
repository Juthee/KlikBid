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
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('categories', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable();
            }

            if (!Schema::hasColumn('categories', 'level')) {
                $table->tinyInteger('level')->default(0);
            }

            if (!Schema::hasColumn('categories', 'icon')) {
                $table->string('icon')->nullable();
            }

            if (!Schema::hasColumn('categories', 'description')) {
                $table->text('description')->nullable();
            }
        });

        // Add foreign key constraint separately
        if (!$this->foreignKeyExists('categories', 'categories_parent_id_foreign')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            }

            if (Schema::hasColumn('categories', 'level')) {
                $table->dropColumn('level');
            }

            if (Schema::hasColumn('categories', 'icon')) {
                $table->dropColumn('icon');
            }

            if (Schema::hasColumn('categories', 'description')) {
                $table->dropColumn('description');
            }
        });
    }

    /**
     * Check if foreign key exists
     */
    private function foreignKeyExists($table, $name)
    {
        $foreignKeys = collect(DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = '{$table}'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        "))->pluck('CONSTRAINT_NAME')->toArray();

        return in_array($name, $foreignKeys);
    }
};
