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
        Schema::create('callbacks', function (Blueprint $table) {
            $table->id();
            $table->unique(["servicenumber", "product_id"]);
            $table->foreignId("product_id_images")->constrained("product_images", "product_id")->onDelete("cascade");
            $table->foreignId("shopvendors_id")->constrained("shopvendors")->onDelete("cascade");
            $table->foreignId("servicenumber")->constrained("serviceinfos")->onDelete("cascade");
            $table->foreignId("product_id")->constrained("products");
            $table->enum("status", ["0401", "1010", "2010"])->default("2010");
            $table->boolean("hide")->default(0);
            $table->boolean("view")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('callbacks');
    }
};
