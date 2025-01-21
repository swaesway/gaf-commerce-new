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
        Schema::create('shopvendors', function (Blueprint $table) {
            $table->id();
            $table->unique(["email", "telephone"]);
            $table->string('shopname')->unique();
            $table->string('email');
            $table->string('telephone');
            $table->string('location');
            $table->string('region');
            $table->boolean('approved')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopvendors');
    }
};
