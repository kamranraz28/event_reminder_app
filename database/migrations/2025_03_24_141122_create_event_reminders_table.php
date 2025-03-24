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
        Schema::create('event_reminders', function (Blueprint $table) {
            $table->id();
            $table->string('event_id')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('event_date');
            $table->enum('status', ['upcoming', 'completed'])->default('upcoming');
            $table->json('emails')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_reminders');
    }
};
