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
        Schema::create('detail_tutorials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_tutorial_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['text', 'image', 'code', 'url']);
            $table->text('content');
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true); // true = show
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_tutorials');
    }
};
