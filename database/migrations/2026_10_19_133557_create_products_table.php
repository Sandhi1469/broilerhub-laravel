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
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id('product_id')->autoIncrement();
                $table->string('name', 150);
                $table->text('description')->nullable();
                $table->foreignId('category_id');
                $table->decimal('price', 12, 2);
                $table->string('img')->nullable();
                $table->integer('stock')->default(0);
                $table->string('slug')->unique();
                $table->timestamps();

                // Foreign key ke categories
                $table->foreign('category_id')
                    ->references('category_id')
                    ->on('categories')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
