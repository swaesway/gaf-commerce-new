<?php

use App\Models\ShopVendor;
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
            $table->unsignedBigInteger('shopvendor_id');
            $table->foreign('shopvendor_id')->references('id')->on('shopvendors')->onDelete('cascade');
            $table->string('productid')->unique()->nullable();
            $table->string('title');
            $table->string('category');
            $table->decimal('price', 10, 2);
            $table->text('description');
            $table->boolean('frozen')->default(0);
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
