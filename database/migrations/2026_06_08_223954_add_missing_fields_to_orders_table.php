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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('country')->nullable()->after('city');
            $table->string('shipping_method')->default('Free Shipping')->after('total_amount');
            $table->string('payment_method')->default('Cash / Bank Transfer')->after('shipping_method');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['country', 'shipping_method', 'payment_method']);
        });
    }
};
