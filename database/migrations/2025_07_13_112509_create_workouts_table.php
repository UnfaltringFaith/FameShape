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
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->string('name'); // Title of the workout
            $table->text('description')->nullable(); // Description of the workout
            $table->enum('privacy', ['public', 'private', 'shared'])->default('public'); // Privacy status of the workout
            $table->integer('duration')->default(0); // Duration in minutes
            $table->integer('calories_burned')->default(0); // Calories burned
            $table->dateTime('workout_date')->nullable(); // Scheduled date and time for the workout
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};
