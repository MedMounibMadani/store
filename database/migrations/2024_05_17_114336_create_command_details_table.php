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
        Schema::create('command_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('count');
            $table->bigInteger('article_id')->unsigned();
            $table->bigInteger('command_id')->unsigned();    
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->foreign('command_id')->references('id')->on('commands')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('command_details');
    }
};
