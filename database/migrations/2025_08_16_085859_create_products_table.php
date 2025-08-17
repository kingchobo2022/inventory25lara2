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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('상품이름');
            $table->string('sku')->unique()->comment('SKU');
            $table->integer('quantity')->default(0)->comment('수량');
            $table->integer('price')->comment('가격');
            $table->string('image', 255)->nullable()->comment('상품이미지');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
