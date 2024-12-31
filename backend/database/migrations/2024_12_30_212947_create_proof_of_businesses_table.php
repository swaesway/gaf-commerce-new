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
        Schema::create('proof_of_businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("shopvendor_id")->constrained("shopvendors")->onDelete("cascade");
            $table->string("proof_of_business");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proof_of_businesses');
    }
};
