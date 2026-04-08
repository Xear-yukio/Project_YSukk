<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('old_price', 15, 2)->nullable();
            $table->string('discount')->nullable(); // Percent string e.g. "40%"
            $table->integer('rating')->default(0);
            $table->integer('reviews')->default(0);
            $table->string('main_image')->nullable();
            $table->json('gallery')->nullable();
            $table->json('colors')->nullable();
            $table->json('breadcrumbs')->nullable(); // Keep for legacy/filtering if needed
            $table->string('badge')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
