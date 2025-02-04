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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unique(["servicenumber", "product_id"]);
            $table->foreignId("servicenumber")->constrained("serviceinfos")->onDelete("cascade");
            $table->unsignedBigInteger('shopvendor_id');
            $table->foreign("shopvendor_id")->references("id")->on("shopvendors")->onDelete("cascade");
            $table->foreignId("product_id")->constrained("products")->onDelete("cascade");
            $table->float("rating");
            $table->longText("comment");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
