<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->after('id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('price', 12, 2)->after('description')->nullable();
        });

        DB::statement('ALTER TABLE products MODIFY category VARCHAR(255) NULL');
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'price']);
        });

        DB::statement('ALTER TABLE products MODIFY category VARCHAR(255) NOT NULL');
    }
};
