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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->string("servicenumber"); // Remove unique constraint if multiple wishlists can share the same service number
            $table->foreign("servicenumber")
                ->references("servicenumber")
                ->on("serviceinfos")
                ->onDelete("cascade");
            $table->foreignId("product_id")
                ->constrained("products")
                ->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
