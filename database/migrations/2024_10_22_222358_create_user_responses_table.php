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
        Schema::create('user_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Relación con `users`
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');  // Relación con `quizzes`
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');  // Relación con `questions`
            $table->boolean('is_correct'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_responses');
    }
};
