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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the exercise
            $table->text('description')->nullable(); // Description of the exercise
            $table->string('image')->nullable(); // Path to the image
            $table->enum('type', ['strength', 'cardio', 'flexibility'])->nullable();
            $table->integer('notes')->default(0); // Duration in seconds for cardio exercises
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
