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
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('category_id')->unsigned()->nullable();    
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->float('price', 8, 2);
            $table->float('installation_fees', 8, 2);
            $table->float('delivery_fees', 8, 2);
            $table->integer('count');
            $table->integer('days_to_delivery');
            $table->float('discount', 3, 1);
            $table->boolean('visibility')->default(1);
            $table->integer('vues')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
