<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('phishing_emails', function (Blueprint $table) {           
            $table->id();
            $table->string('subject');
            $table->text('body');
            $table->text('explanation');
            $table->foreignId('topic_id')->constrained('phishing_topics')->onDelete('cascade');
            $table->json('emotional_triggers')->nullable();
            $table->json('persuasions')->nullable();
            $table->foreignId('llm_id')->constrained('llms')->onDelete('cascade');
            $table->text('prompt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('phishing_emails');
    }
};
