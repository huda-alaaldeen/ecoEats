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
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->integer('available_count')->default(0);
            $table->integer('price');
            $table->string('image')->nullable();
            $table->string('category');         
            $table->text('description')->nullable(); 
            $table->boolean('contains_meat')->default(false);;
            $table->boolean('contains_chicken')->default(false);; 
            $table->enum('status', ['available', 'reserved'])->default('available');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
