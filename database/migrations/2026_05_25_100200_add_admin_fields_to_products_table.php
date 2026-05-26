<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('category_id')->constrained()->nullOnDelete();
            $table->string('keywords')->nullable()->after('name');
            $table->text('detail')->nullable()->after('description');
            $table->unsignedInteger('min_stock')->default(0)->after('inventory');
            $table->unsignedInteger('discount')->default(0)->after('min_stock');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['keywords', 'detail', 'min_stock', 'discount']);
        });
    }
};
