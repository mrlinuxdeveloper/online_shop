<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            Schema::hasColumn('product_images', 'is_main') &&
            !Schema::hasColumn('product_images', 'is_primary')
        ) {
            Schema::table('product_images', function (Blueprint $table) {
                $table->renameColumn('is_main', 'is_primary');
            });

            return;
        }

        if (!Schema::hasColumn('product_images', 'is_primary')) {
            Schema::table('product_images', function (Blueprint $table) {
                $table->boolean('is_primary')->default(false);
            });
        }
    }

    public function down(): void
    {
        if (
            Schema::hasColumn('product_images', 'is_primary') &&
            !Schema::hasColumn('product_images', 'is_main')
        ) {
            Schema::table('product_images', function (Blueprint $table) {
                $table->renameColumn('is_primary', 'is_main');
            });
        }
    }
};
