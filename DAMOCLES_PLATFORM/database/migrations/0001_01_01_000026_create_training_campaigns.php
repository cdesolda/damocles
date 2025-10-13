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
        Schema::create('training_campaigns', function (Blueprint $table) {           
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('threat_id')->constrained('threats')->onDelete('cascade');
            $table->foreignId('llm_id')->nullable()->constrained('llms')->onDelete('cascade');
            $table->text('prompt')->nullable();
            $table->enum('type',['Text','Audio'])->default('Text');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->enum('state',['Draft','Ready','Live', 'Completed'])->default('Draft');
            $table->date('expiration_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('training_campaigns');
    }
};
